<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Hash;
use Yajra\DataTables\Facades\DataTables;

class AdminUserController extends Controller
{
    private $admin;
    private $role;

    public function __construct(Admin $admin, Role $role)
    {
        $this->middleware('CheckPermission:adminuser');
        $this->admin = $admin;
        $this->role = $role;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.adminuser.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = $this->role->all(['id','display_name']);
        return view('admin.adminuser.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userModel = $this->admin;
        $userModel->email = $request['email'];
        $userModel->name = $request['name'];
        $userModel->password = bcrypt($request['password']);
        $userModel->save();
        $userModel->attachRole($request['role']);
        flash('后台用户新增成功', 'success');

        return redirect('admin/adminuser');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userData = $this->admin->editViewData($id);
        $roles = $this->role->all(['id','display_name']);

        return view('admin.adminuser.edit',compact('roles','userData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $adminUser = $this->admin->find($id);
        if ($request['old_password'] && !Hash::check($request['old_password'], $adminUser->password)) {
            abort(401, '原密码不正确！');
        }
        $roleId = $request['role'];
        if (empty($request['password'])) {
            unset($request['password']);
        }
        $attr['password'] = bcrypt($request['password']);

        $res = $adminUser->update($attr);

        $userRole = DB::table('role_user')->where('user_id', '=', $id)->first();
        if ($userRole) {
            DB::table('role_user')->where('user_id', '=', $id)->update(['role_id' => $roleId]);
        } else {
            DB::table('role_user')->insert(['user_id' => $id, 'role_id' => $roleId]);
        }
        if ($res) {
            flash('修改成功!', 'success');
        } else {
            flash('修改失败!', 'error');
        }
        return redirect('admin/adminuser');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ajaxIndex(Request $request)
    {
        $admin = $this->admin->all();

        foreach ($admin as $item) {
            $item->role = $item->roles->toArray()[0]['display_name'];//获取关联的角色
        }

        return Datatables::of($admin)
            ->addColumn('action', function($admin){

                return "<a href='".url('admin').'/adminuser/'.$admin->id."/edit'><button type='button' class='btn btn-success btn-xs'><i class='fa fa-pencil'> 编辑</i></button></a> " .
                    "<a href='javascript:;' data-id='".$admin->id."' class='btn btn-danger btn-xs destroy'>" .
                    "<i class='fa fa-trash'> 删除</i>" .
                    "<form action='".url('admin/adminuser/'.$admin->id)."' style='display: none;' method='POST'  name='delete_item_".$admin->id."" .
                    "method_field('DELETE').csrf_field()".
                    "</form></a> ";
            })->rawColumns(['action'])->make(true);
    }
}

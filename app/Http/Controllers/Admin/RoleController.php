<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public $role;
    public $permission;

    public function __construct(Permission $permission, Role $role)
    {
        $this->middleware('CheckPermission:role');
        $this->role = $role;
        $this->permission = $permission;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = $this->permission->all(['id','display_name']);
        return view('admin.role.create',compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = $this->role;
        $role->name = $request['name'];
        $role->display_name = $request['display_name'];
        $role->description = $request['description'];
        $role->save();
        $role->attachPermissions($request['permission']);

        flash('角色新增成功', 'success');

        return redirect('admin/role');
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
        $data = $this->editViewData($id);

        return view('admin.role.edit',compact('data'));
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
        $permissions = $request['permission'];

        $res = $this->role->find($id);

        $res->update($request->all());

        $res->perms()->sync($permissions);

        return redirect('admin/role');
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
        $role = $this->role->all();

        return Datatables::of($role)
            ->addColumn('action', function($role){

                return "<a href='".url('admin').'/role/'.$role->id."/edit'><button type='button' class='btn btn-success btn-xs'><i class='fa fa-pencil'> 编辑</i></button></a> " .
                    "<a href='javascript:;' data-id='".$role->id."' class='btn btn-danger btn-xs destroy'>" .
                    "<i class='fa fa-trash'> 删除</i>" .
                    "<form action='".url('admin/role/'.$role->id)."' style='display: none;' method='POST'  name='delete_item_".$role->id."" .
                    "method_field('DELETE').csrf_field()".
                    "</form></a> ";
            })->rawColumns(['action'])->make(true);
    }

    private function editViewData($id)
    {
        $role = $this->role->find($id,['id','name','display_name','description'])->toArray();

        $rolePermissionList = DB::table('permission_role')->where('role_id', $id)->get(['permission_id'])->toArray();

        $rolePermission = array_column($rolePermissionList, 'permission_id');

        $permissions = Permission::all(['id','display_name'])->toArray();

        return compact('role','rolePermission','permissions');
    }
}

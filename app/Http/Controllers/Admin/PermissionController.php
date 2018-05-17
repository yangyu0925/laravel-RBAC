<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public $permission;

    public function __construct(Permission $permission)
    {
        $this->middleware('CheckPermission:permission');
        $this->permission = $permission;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.permission.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $res = $this->permission->create($request->all());
        if ($res) {
            flash('权限新增成功', 'success');
        } else {
            flash('权限新增失败', 'error');
        }
        return redirect('admin/permission');
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
        //
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
        //
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
        $permission = $this->permission->all();
        return Datatables::of($permission)
            ->addColumn('action', function($permission){

                return "<a href='".url('admin').'/permission/'.$permission->id."/edit'><button type='button' class='btn btn-success btn-xs'><i class='fa fa-pencil'> 编辑</i></button></a> " .
                    "<a href='javascript:;' data-id='".$permission->id."' class='btn btn-danger btn-xs destroy'>" .
                    "<i class='fa fa-trash'> 删除</i>" .
                    "<form action='".url('admin/permission/'.$permission->id)."' style='display: none;' method='POST'  name='delete_item_".$permission->id."" .
                    "method_field('DELETE').csrf_field()".
                    "</form></a> ";
            })->rawColumns(['action'])->make(true);
    }
}

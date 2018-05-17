<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    private $menu;

    public function __construct(Menu $menu)
    {
        $this->middleware('CheckPermission:menus');
        $this->menu = $menu;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $field = ['id', 'name', 'url', 'slug', 'icon', 'sort', 'parent_id', 'updated_at'];

        $menus = $this->menu->getAll($field);

        return view('admin.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $topMenus = $this->menu->where('parent_id', 0)->get();

        return view('admin.menu.create',compact('topMenus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $res = $this->menu->create($request->all());
        if ($res){
            flash('菜单新增成功','success');
        }else{
            flash('菜单新增失败','error');
        }
        return redirect('admin/menus');
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
        $topMenus = $this->menu->where('parent_id', 0)->get();
        $menu = $this->menu->find($id)->toArray();
        return view('admin.menu.edit',compact('topMenus','menu'));
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
        $model = $this->menu->findOrFail($id);
        $model->fill($request->all());
        $model->save();
        if ($model){
            flash('菜单保存成功')->success();
        }else{
            flash('菜单保存失败')->error();
        }
        return redirect('admin/menus');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $has_child = $this->menu->where('parent_id', $id)->first();
        if (!is_null($has_child)) {
            flash('菜单删除失败, 还有子菜单未删除')->warning();
            return redirect('admin/menus');
        }
        $res = $this->menu->destroy($id);
        if ($res){
            flash('菜单删除成功')->success();
        }else{
            flash('菜单删除失败')->error();
        }
        return redirect('admin/menus');
    }
}

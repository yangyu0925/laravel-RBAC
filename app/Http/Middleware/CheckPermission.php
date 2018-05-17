<?php

namespace App\Http\Middleware;

use Closure;
use Route;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $m)
    {
        $routeName = Route::currentRouteName();
        $permission = '';
        switch ($routeName){
            case "{$m}.index":
            case "{$m}.ajaxIndex": $permission = "{$m}.list";     break;
            case "{$m}.create":
            case "{$m}.store":     $permission = "{$m}.add";      break;
            case "{$m}.edit":
            case "{$m}.update":    $permission = "{$m}.edit";     break;
            case "{$m}.destroy":   $permission = "{$m}.delete";   break;
            default : break;
        }
        if (!$permission){
            abort(401,'系统没有权限，请修改权限验证中间件\\App\\Http\\Middleware\\CheckPermission！');
        }
        if (!$request->user('admin')->can($permission)){
            abort(401,'您没有权限进行此次操作！');
        }
        return $next($request);
    }
}

<?php

namespace App\Http\ViewComposers;

use App\Models\Menu;
use Illuminate\Contracts\View\View;


class AdminMenuComposer
{
    protected $menu;

    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    public function compose(View $view)
    {
        $view->with('adminMenus', $this->menu->getMenuComposerData());
    }
}
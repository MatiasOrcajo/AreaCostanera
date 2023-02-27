<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuRequest;
use App\Models\Menu;
use App\Models\MenuEspecial;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    //

    public function index()
    {
        return view('menus');
    }

    public function listMenus()
    {
        $data = Menu::all()->map(function ($query) {
            return [
                "nombre" => $query->nombre,
                "precio" => $query->precio,
                "id" => $query->id
            ];
        });

        return DataTables::of($data)->make(true);
    }

    public function storeMenu(StoreMenuRequest $request)
    {
        $validated = $request->validated();
        Menu::create($validated);

        return back()->with('success', 'Menu agregado');
    }

    public function indexSpecialMenu()
    {
        return view('specialMenus');
    }

    public function listSpecialMenus()
    {
        $data = MenuEspecial::all()->map(function ($query) {
            return [
                'nombre' => $query->nombre,
                'id' => $query->id
            ];
        });

        return DataTables::of($data)->make(true);
    }

    public function storeSpecialMenu(Request $request)
    {
        $menu = new MenuEspecial();
        $menu->nombre = $request->nombre;
        $menu->save();

        return back()->with('success', 'Menu agregado');
    }

    public function editMenu(Menu $menu)
    {
        //
    }
}

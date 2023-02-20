<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuRequest;
use App\Models\Menu;
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

    public function editMenu(Menu $menu)
    {
        //
    }
}

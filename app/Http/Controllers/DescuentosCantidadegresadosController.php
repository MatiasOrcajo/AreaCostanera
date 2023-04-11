<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DescuentosCantidadegresados;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DescuentosCantidadegresadosController extends Controller
{
    public function index()
    {
        $descuento = DescuentosCantidadegresados::first();

        return view('descuentoEgresados', compact('descuento'));
    }

    public function listDiscounts()
    {
        $data = DescuentosCantidadegresados::all()
                    ->map(function ($query){
                      return [
                          "20_a_30" => $query->descuento_20_a_30.'%',
                          "31_a_50" => $query->descuento_31_a_50.'%',
                          "51_a_70" => $query->descuento_51_a_70.'%',
                          "71_a_100" => $query->descuento_71_a_100.'%',
                          "101_a_150" => $query->descuento_101_a_150.'%',
                          "151_o_mas" => $query->descuento_151_o_mas.'%',
                      ]  ;
                    });

        return DataTables::of($data)->make(true);
    }

    public function edit(Request $request)
    {
        $descuento = DescuentosCantidadegresados::first();

        $descuento->update([
            "descuento_20_a_30" => $request["20_a_30"],
            "descuento_31_a_50" => $request["31_a_50"],
            "descuento_51_a_70" => $request["51_a_70"],
            "descuento_71_a_100" => $request["71_a_100"],
            "descuento_101_a_150" => $request["101_a_150"],
            "descuento_151_o_mas" => $request["151_o_mas"],
        ]);

        return back();
    }
}

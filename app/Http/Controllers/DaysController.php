<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDaysRequest;
use App\Models\Dia;
use App\Models\DiasDescuentos;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DaysController extends Controller
{
    public function index()
    {
        $days = Dia::all();

        return view('eventsDays', compact('days'));
    }

    public function listDays()
    {
        $data = Dia::all()->map(function ($query) {
            return [
                "nombre" => $query->nombre,
                "20_a_30" => $query->descuentos["descuento_20_a_30"],
                "31_a_50" => $query->descuentos["descuento_31_a_50"],
                "51_a_70" => $query->descuentos["descuento_51_a_70"],
                "71_a_100" => $query->descuentos["descuento_71_a_100"],
                "101_a_150" => $query->descuentos["descuento_101_a_150"],
                "151_o_mas" => $query->descuentos["descuento_151_o_mas"],
                "id" => $query->id
            ];
        });

        return DataTables::of($data)->make(true);
    }

    public function storeDays (StoreDaysRequest $request)
    {
        $dia = new Dia();
        $dia->nombre = $request->nombre;
        $dia->save();

        $dia_descuentos = new DiasDescuentos();
        $dia_descuentos->dia_id = $dia->id;
        $dia_descuentos->save();

        return back()->with('success', 'Grupo creado');
    }

    public function edit(Dia $day,Request $request)
    {
        $dias_descuentos = DiasDescuentos::where('dia_id', $day->id)->first();
        $dias_descuentos->descuento_20_a_30 = $request['20_a_30'];
        $dias_descuentos->descuento_31_a_50 = $request['31_a_50'];
        $dias_descuentos->descuento_51_a_70 = $request['51_a_70'];
        $dias_descuentos->descuento_71_a_100 = $request['71_a_100'];
        $dias_descuentos->descuento_101_a_150 = $request['101_a_150'];
        $dias_descuentos->descuento_151_o_mas = $request['151_o_mas'];
        $dias_descuentos->save();

        $day->nombre = $request->nombre;
        $day->save();

        return back()->with('success', 'Día editado correctamente');
    }
}

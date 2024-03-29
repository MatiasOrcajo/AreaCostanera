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
                "descuento" => $query->descuento.'%',
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

        UserController::history('Creó el día de descuento '. $dia->nombre);

        return back()->with('success', 'Grupo creado');
    }

    public function edit(Dia $day,Request $request)
    {

        $beforeEditDay = 'Versión anterior: <br>'.
            'Nombre: '. $day->nombre .
            'Descuento: '. $day->descuento;

        $day->descuento = $request->descuento;
        $day->nombre = $request->nombre;
        $day->save();

        UserController::history('Editó el día de descuento ID '. $day->id . ' <br>' . $beforeEditDay. '<br>'.
            'Versión nueva: <br>'.

            'Nombre: '.$day->nombre .'<br>'.
            'Descuento: '. $day->descuento

        );

        return back()->with('success', 'Día editado correctamente');
    }
}

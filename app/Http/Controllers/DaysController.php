<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDaysRequest;
use App\Models\Dia;
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
                "id" => $query->id
            ];
        });

        return DataTables::of($data)->make(true);
    }

    public function storeDays (StoreDaysRequest $request)
    {
        $validated = $request->validated();
        Dia::create($validated);

        return back()->with('success', 'Grupo creado');
    }

    public function edit(Dia $day,Request $request)
    {
        $day->update($request->toArray());

        return back()->with('success', 'Día editado correctamente');
    }
}

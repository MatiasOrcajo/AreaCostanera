<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGraduatePartyRequest;
use App\Models\Egresados;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GraduatePartyController extends Controller
{

    public function index()
    {
        $graduateParties = Egresados::all();

        return view('dashboard', compact('graduateParties'));
    }


    /**
     * Create new graduate party
     * @param StoreGraduatePartyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function createGraduateParty(StoreGraduatePartyRequest $request)
    {
        Egresados::create([
            'escuela_id' => $request->escuela_id,
            'curso' => $request->curso_id,
            'fecha' => $request->fecha,
            'fecha_pago' => $request->fecha_pago,
            'dia_id' => $request->dia_id,
            'menu_id' => $request->menu_id,
            'slug' => Str::slug($request->escuela_id.$request->curso_id.$request->fecha),
            'forma_pago_id' => $request->forma_pago_id
        ]);

        return back()->with('success', 'Fiesta añadida');
    }


    public function showGraduateParty(Egresados $egresados)
    {

    }
}

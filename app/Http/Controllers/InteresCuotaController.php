<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InteresCuota;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class InteresCuotaController extends Controller
{
    public function index()
    {
        $interes = InteresCuota::first();

        return view('interesCuotas', compact('interes'));
    }

    public function listInteres()
    {
        $data = InteresCuota::all()->map(function($query){
            return [
                'id' => $query->id,
                'interes' => $query->interes
            ];
        });

        return DataTables::of($data)->make(true);
    }

    public function edit(Request $request)
    {
        $interes = InteresCuota::first();
        $interes->interes = $request->interes;
        $interes->save();

        return back();
    }
}

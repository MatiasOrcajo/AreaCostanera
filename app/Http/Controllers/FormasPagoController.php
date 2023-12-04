<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFormaPagoRequest;
use App\Models\FormasPago;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FormasPagoController extends Controller
{
    public function index()
    {
        $paymentTypes = FormasPago::all();

        return view('formasDePago', compact('paymentTypes'));
    }

    public function listFormasPago()
    {
        $data = FormasPago::all()->map(function ($query) {
            return [
                'id' => $query->id,
                'nombre' => $query->nombre,
                'interes' => $query->interes
            ];
        });

        return DataTables::of($data)->make(true);
    }

    public function storeFormaPago(StoreFormaPagoRequest $request)
    {
        $validated = $request->validated();

        $formaPago = FormasPago::create($validated);

        UserController::history('Creó la forma de pago '. $formaPago->nombre);

        return back()->with('success', 'Forma de Pago añadida');
    }

    public function edit(FormasPago $paymentType, Request $request)
    {

        $beforeEditPaymentType = 'Versión anterior: <br>'.
            'Nombre: '. $paymentType->nombre .
            'Interés: '. $paymentType->interes;

        $paymentType->update($request->toArray());

        UserController::history('Editó el método de pago ID '. $paymentType->id . ' <br>' . $beforeEditPaymentType. '<br>'.
            'Versión nueva: <br>'.

            'Nombre: '.$paymentType->nombre .'<br>'.
            'Interés: '. $paymentType->interes

        );

        return back()->with('success', 'Forma de pago editada');
    }


}

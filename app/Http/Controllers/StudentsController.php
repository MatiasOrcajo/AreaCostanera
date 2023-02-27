<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\EstudianteFamiliares;
use App\Models\Menu;
use App\Models\MenuEspecial;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;

class StudentsController extends Controller
{
    public function store(Request $request)
    {

        if($request->has('is_graduated')){
            $student = new Estudiante();
            $student->nombre = $request->nombre;
            $student->egresado_id = $request->event_id;
            $student->menu_id = $request->menu_id;
            $student->menu_especial_id = $request->menu_especial_id ?? null;
            $student->fecha_pago = Carbon::createFromFormat('Y-m-d', $request->fecha_pago)->format('d-m-Y');
            $student->forma_pago_id = $request->forma_pago_id;
            $student->familiares = $request->familiares;
            $student->menores_12 = $request->menores_12;
            $student->menores_5 = $request->menores_5;
            $student->email = $request->email;
            $student->telefono = $request->telefono;

            $selectedMenuPrice = Menu::find($request->menu_id)->precio;

            $studentTotalAmountWithoutDiscounts = $selectedMenuPrice * ($request->familiares + 1);
            $studentMinorsOfTwelveDiscount = $selectedMenuPrice * $student->menores_12 / 2; //50%
            $studentMinorsOfFiveDiscount = $selectedMenuPrice * $student->menores_5; //100%
            $totalWithoutDiscounts = $studentTotalAmountWithoutDiscounts - $studentMinorsOfTwelveDiscount -
                $studentMinorsOfFiveDiscount;

            $student->total =
                $totalWithoutDiscounts + (($totalWithoutDiscounts * $student->paymentType->interes) / 100);
            $student->save();
        }
        else{
            $family = new EstudianteFamiliares();
            $family->nombre = $request->nombre;
            $family->estudiante_id = $request->estudiante_id;
            $family->menu_especial = $request->menu_especial_id ?? null;

            $family->save();

        }

        return back()->with('success', 'Egresado añadido');

    }

    public function getStudentFamily($id)
    {
//        dd($id);
        $student = Estudiante::find($id);

        $data = $student->people->map(function($query){
           return[
                "nombre" => $query->nombre,
                "menu_especial" => $query->menu_especial ? MenuEspecial::find($query->menu_especial)->nombre : '-'
           ];
        });

        return DataTables::of($data)->make(true);
    }
}

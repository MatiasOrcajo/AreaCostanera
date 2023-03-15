<?php

namespace App\Http\Controllers;

use App\Models\Egresados;
use App\Models\Estudiante;
use App\Models\EstudianteFamiliares;
use App\Models\Menu;
use App\Models\MenuEspecial;
use App\Models\Pago;
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
//            $student->menu_id = $request->menu_id;
            $student->menu_especial_id = $request->menu_especial_id ?? null;
            $student->fecha_pago = $request->fecha_pago;
            $student->forma_pago_id = $request->forma_pago_id;
            $student->medio_pago_id = $request->medio_pago_id;
            $student->familiares = $request->familiares;
            $student->menores_12 = $request->menores_12;
            $student->menores_5 = $request->menores_5;
            $student->email = $request->email;
            $student->telefono = $request->telefono;
            $student->save();
        }
        else{
            $family = new EstudianteFamiliares();
            $family->nombre = $request->nombre;
            $family->estudiante_id = $request->estudiante_id;
            $family->menu_especial = $request->menu_especial_id ?? null;
            $family->telefono = $request->telefono;

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
               'id' => $query->id,
                "nombre" => $query->nombre,
                "menu_especial" => $query->menu_especial ? MenuEspecial::find($query->menu_especial)->nombre : '-',
               "telefono" => $query->telefono
           ];
        });

        return DataTables::of($data)->make(true);
    }

    public function edit(Estudiante $graduate, Request $request)
    {
        $graduate->update($request->toArray());
        $graduate->total = $graduate->getTotalPrice();
        $graduate->save();

        return back()->with('success', 'Estudiante editado correctamente');
    }

    public function showStudent(Estudiante $student)
    {
        $specialMenu = MenuEspecial::all();

        return view('showStudent', compact('student', 'specialMenu'));
    }

    public function deleteFamily(EstudianteFamiliares $family)
    {
        dd($family);
    }

    public function deleteStudent(Estudiante $student)
    {
        $student->delete();

        return true;
    }

    public function payPartOfDebt(Estudiante $student,Request $request)
    {
        $pago = new Pago();
        $pago->estudiante_id = $student->id;
        $pago->amount = $request->pago;

        $pago->save();

        return back();
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Egresados;
use App\Models\Estudiante;
use App\Models\EstudianteCuota;
use App\Models\EstudianteFamiliares;
use App\Models\EstudiantesResumen;
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
        $student = new Estudiante();
        $student->nombre = $request->nombre;
        $student->egresado_id = $request->event_id;
//            $student->menu_id = $request->menu_id;
        $student->menu_especial_id = $request->menu_especial_id ?? null;
        $student->fecha_pago = $request->fecha_pago;
        $student->forma_pago_id = $request->forma_pago_id;
        $student->medio_pago_id = $request->medio_pago_id;
        /**$student->familiares = $request->familiares;
        $student->menores_12 = $request->menores_12;
        $student->menores_5 = $request->menores_5;**/
        $student->email = $request->email;
        $student->telefono = $request->telefono;
        $student->save();

        self::createDues($request->forma_pago_id, $student);

        return back()->with('success', 'Egresado añadido');

    }

    public function storeFamily(Request $request)
    {
        $student = Estudiante::find($request->estudiante_id);
        $family = new EstudianteFamiliares();
        $family->nombre = $request->nombre;
        $family->estudiante_id = $request->estudiante_id;
        $family->menu_especial = $request->menu_especial_id ?? null;
        $family->tipo = $request->tipo;
        $family->telefono = $request->telefono;

        if(isset($student->resumen)){
            $family->fuera_termino = 1;
            if($family->tipo == 'menor_12'){
                $family->total = $student->event->menu->precio / 2;
            }
            elseif($family->tipo == 'adulto'){
                $family->total = $student->event->menu->precio;
            }
            else{
                $family->total = 0;
            }

            $resumenTotal = $student->resumen;
            $resumenTotal->total += $family->total;
            $resumenTotal->save();
        }
        else{
            $family->fuera_termino = 0;
        }

        $family->save();

        return back()->with('success', 'Familiar añadido');
    }

    private static function createDues(int $id, Estudiante $student)
    {
        if ($id == 2) {
            $cuota = new EstudianteCuota();
            $cuota->estudiante_id = $student->id;
            $cuota->fecha_estipulada = Carbon::parse($student->fecha_pago);
            $cuota->status = 0;
            $cuota->save();
//
            $cuota = new EstudianteCuota();
            $cuota->estudiante_id = $student->id;
            $cuota->fecha_estipulada = Carbon::parse($student->fecha_pago)->add(1, 'month');
            $cuota->status = 0;
            $cuota->save();

            $cuota = new EstudianteCuota();
            $cuota->estudiante_id = $student->id;
            $cuota->fecha_estipulada = Carbon::parse($student->fecha_pago)->add(2, 'month');
            $cuota->status = 0;
            $cuota->save();
        }

        if ($id == 3) {
            $cuota = new EstudianteCuota();
            $cuota->estudiante_id = $student->id;
            $cuota->fecha_estipulada = Carbon::parse($student->fecha_pago);
            $cuota->status = 0;
            $cuota->save();
//
            $cuota = new EstudianteCuota();
            $cuota->estudiante_id = $student->id;
            $cuota->fecha_estipulada = Carbon::parse($student->fecha_pago)->add(1, 'month');
            $cuota->status = 0;
            $cuota->save();

            $cuota = new EstudianteCuota();
            $cuota->estudiante_id = $student->id;
            $cuota->fecha_estipulada = Carbon::parse($student->fecha_pago)->add(2, 'month');
            $cuota->status = 0;
            $cuota->save();

            $cuota = new EstudianteCuota();
            $cuota->estudiante_id = $student->id;
            $cuota->fecha_estipulada = Carbon::parse($student->fecha_pago)->add(3, 'month');
            $cuota->status = 0;
            $cuota->save();

            $cuota = new EstudianteCuota();
            $cuota->estudiante_id = $student->id;
            $cuota->fecha_estipulada = Carbon::parse($student->fecha_pago)->add(4, 'month');
            $cuota->status = 0;
            $cuota->save();

            $cuota = new EstudianteCuota();
            $cuota->estudiante_id = $student->id;
            $cuota->fecha_estipulada = Carbon::parse($student->fecha_pago)->add(5, 'month');
            $cuota->status = 0;
            $cuota->save();
        }
    }

    public function getStudentFamily($id)
    {
//        dd($id);
        $student = Estudiante::find($id);

        $data = $student->people->map(function ($query) {
            return [
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
        $graduate->cuotas->map(function ($query) {
            $query->delete();
        });

        self::createDues($graduate->forma_pago_id, $graduate);
        $graduate->update($request->toArray());
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
        if($family->fuera_termino == 1){
            $estudianteResumen = Estudiante::find($family->estudiante_id)->resumen;
            $estudianteResumen->total -= $family->total;
            $estudianteResumen->save();
        }
       $family->delete();

       return back();
    }

    public function editFamily(Request $request, EstudianteFamiliares $family)
    {
        $family->nombre = $request->nombre;

        if ($request->menu_especial_id == "Seleccionar menú especial"){
            $family->menu_especial = null;
        }
        else{
            $family->menu_especial = $request->menu_especial_id;
        }

        $family->telefono = $request->telefono;

        $family->save();

        return back();
    }

    public function deleteStudent(Estudiante $student)
    {
        $student->delete();

        return true;
    }

    public function payPartOfDebt(Estudiante $student, Request $request)
    {
        $pago = new Pago();
        $pago->estudiante_id = $student->id;
        $pago->amount = $request->pago;
        $pago->tipo = 'adelanto';
        $pago->save();

        return back();
    }

    public function deleteAdvancedPayment(Pago $payment)
    {
        $payment->delete();

        return back();
    }

    public function closePrice(Estudiante $estudiante)
    {
        $resumen = new EstudiantesResumen();
        $resumen->estudiante_id = $estudiante->id;
        $resumen->precio_unitario = $estudiante->event->menu->precio;
        $resumen->descuento_egresados = $estudiante->event->getEventDiscountByAmountOfStudents();
        $resumen->precio_adulto_egresado = $estudiante->getPriceOfAdults();
        $resumen->menores_12 = $estudiante->getPriceOfMinorsOfTwelve();
        $resumen->iva = $estudiante->getTotalPrice() * $estudiante->medioDePago->iva / 100;
        $resumen->total = $estudiante->getTotalPrice();

        $resumen->save();

        return true;
    }

}

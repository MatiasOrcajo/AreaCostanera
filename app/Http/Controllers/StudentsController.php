<?php

namespace App\Http\Controllers;

use App\Models\Egresados;
use App\Models\Estudiante;
use App\Models\EstudianteCuota;
use App\Models\EstudianteFamiliares;
use App\Models\EstudiantesResumen;
use App\Models\FormasPago;
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
        $formaPago = FormasPago::find($id);
        switch ($formaPago->nombre){
            case '2 cuotas':
                $cuotas = 2;
                break;
            case '3 cuotas':
                $cuotas = 3;
                break;
            case '4 cuotas':
                $cuotas = 4;
                break;
            case '5 cuotas':
                $cuotas = 5;
                break;
            case '6 cuotas':
                $cuotas = 6;
                break;
            case '7 cuotas':
                $cuotas = 7;
                break;
            case '8 cuotas':
                $cuotas = 8;
                break;
            case '9 cuotas':
                $cuotas = 9;
                break;
            case '10 cuotas':
                $cuotas = 10;
                break;
            case '11 cuotas':
                $cuotas = 11;
                break;
            case '12 cuotas':
                $cuotas = 12;
                break;
            default:
                $cuotas = 1;
                break;

        }

        for ($i = 0; $i < $cuotas; $i++){
            $cuota = new EstudianteCuota();
            $cuota->estudiante_id = $student->id;
            $cuota->fecha_estipulada = Carbon::parse($student->fecha_pago)->add($i, 'month');
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

        self::createDues($request->forma_pago_id, $graduate);
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
        $resumen->iva = ($estudiante->getPriceOfMinorsOfTwelve() + $estudiante->getPriceOfAdults()) * $estudiante->medioDePago->iva / 100;
        $resumen->total = $estudiante->getTotalPrice();

        $resumen->save();

        return true;
    }

    public function createDiscount(Request $request, Estudiante $student)
    {
        //actualizo el total del resumen con el nuevo descuento
        if($student->resumen){
            $descuentoEspecialEnPesos = $student->resumen->precio_unitario * $student->descuento_especial / 100;
            $student->resumen->total += $descuentoEspecialEnPesos;
            $student->resumen->total -= $student->resumen->precio_unitario * $request->descuento / 100;
            $student->resumen->save();
        }

        $student->descuento_especial = $request->descuento;
        $student->save();

        return back();
    }

}

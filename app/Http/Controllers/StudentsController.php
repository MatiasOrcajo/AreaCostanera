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
        $student->menu_especial_2_id = $request->menu_especial_2_id ?? null;
        $student->fecha_pago = $request->fecha_pago;
        $student->forma_pago_id = $request->forma_pago_id;
        $student->medio_pago_id = $request->medio_pago_id;
        /**$student->familiares = $request->familiares;
        $student->menores_12 = $request->menores_12;
        $student->menores_5 = $request->menores_5;**/
        $student->email = $request->email;
        $student->telefono = $request->telefono;
        $student->observaciones = $request->observaciones;
        $student->save();

        self::createDues($request->forma_pago_id, $student);

        UserController::history('Creó el estudiante '.$student->nombre. ' del evento '.$student->event->school->nombre.' '.$student->event->curso.' '.$student->event->fecha);

        return back()->with('success', 'Egresado añadido');

    }

    public function storeFamily(Request $request)
    {
        $student = Estudiante::find($request->estudiante_id);
        $family = new EstudianteFamiliares();
        $family->nombre = $request->nombre;
        $family->estudiante_id = $request->estudiante_id;
        $family->menu_especial = $request->menu_especial_id ?? null;
        $family->menu_especial_2_id = $request->menu_especial_2_id ?? null;
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

        UserController::history('Creó el invitado '.$family->nombre.' del estudiante '.$student->nombre. ' del evento '.$student->event->school->nombre.' '.$student->event->curso.' '.$student->event->fecha);

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
                "menu_especial_2" => $query->menu_especial_id_2 ? MenuEspecial::find($query->menu_especial_id_2)->nombre : '-',
                "telefono" => $query->telefono
            ];
        });

        return DataTables::of($data)->make(true);
    }

    public function edit(Estudiante $student, Request $request)
    {


        $beforeEditStudent =    'Estudiante: ' . $student->nombre .'<br>'.
                                'ID evento = ' . $student->egresado_id. '<br>' .
                                'ID menu especial: ' . $student->menu_especial_id. '<br>'.
                                'ID menu especial 2: '. $student->menu_especial_2_id. '<br>'.
                                'Fecha primer pago: '. $student->fecha_pago. '<br>'.
                                'ID medio de pago: '. $student->menu_especial_2_id. '<br>'.
                                'ID forma de pago: '. $student->forma_pago_id. '<br>'.
                                'Email: '. $student->email. '<br>'.
                                'Teléfono: '. $student->telefono. '<br>'.
                                'Descuento especial: '. $student->descuento_especial. '<br>'.
                                'Observaciones: '. $student->observaciones. '<br>';

        $student->cuotas->map(function ($query) {
            $query->delete();
        });

        self::createDues($request->forma_pago_id, $student);
        $student->update($request->toArray());
        $student->save();

        UserController::history('Editó el descuento del evento '. $event->school->nombre . ' ' . $event->curso . ' del día ' . $event->fecha. '<br>' .
            'Versión anterior: <br>'.$beforeEditStudent.'<br>'.
            'Versión nueva: <br>'.

            'Estudiante: ' . $student->nombre .'<br>'.
            'ID evento = ' . $student->egresado_id. '<br>' .
            'ID menu especial: ' . $student->menu_especial_id. '<br>'.
            'ID menu especial 2: '. $student->menu_especial_2_id. '<br>'.
            'Fecha primer pago: '. $student->fecha_pago. '<br>'.
            'ID medio de pago: '. $student->menu_especial_2_id. '<br>'.
            'ID forma de pago: '. $student->forma_pago_id. '<br>'.
            'Email: '. $student->email. '<br>'.
            'Teléfono: '. $student->telefono. '<br>'.
            'Descuento especial: '. $student->descuento_especial. '<br>'.
            'Observaciones: '. $student->observaciones. '<br>'
        );

        return back()->with('success', 'Estudiante editado correctamente');
    }

    public function showStudent(Estudiante $student)
    {
        $specialMenu = MenuEspecial::all();

        UserController::history('Ingresó a ver el estudiante ' .$student->nombre. ', del evento '.$student->event->school->nombre . ' ' . $student->event->curso . ' del día ' . $student->event->fecha);

        return view('showStudent', compact('student', 'specialMenu'));
    }

    public function deleteFamily(EstudianteFamiliares $family)
    {
        if($family->fuera_termino == 1){
            $estudianteResumen = Estudiante::find($family->estudiante_id)->resumen;
            $estudianteResumen->total -= $family->total;
            $estudianteResumen->save();
        }

        UserController::history('Eliminó al invitado '.$family->nombre);

       $family->delete();

       return back();
    }

    public function editFamily(Request $request, EstudianteFamiliares $family)
    {

        $beforeEditFamily =     'Invitado: ' . $family->nombre .'<br>'.
                                'ID estudiante = ' . $family->estudiante_id. '<br>' .
                                'ID menu especial: ' . $family->menu_especial. '<br>'.
                                'ID menu especial 2: '. $family->menu_especial_2. '<br>'.
                                'Teléfono: '. $family->telefono. '<br>'.
                                'Tipo: '. $family->tipo. '<br>';

        $family->nombre = $request->nombre;

        if ($request->menu_especial_id == "Seleccionar menú especial"){
            $family->menu_especial = null;
        }
        else{
            $family->menu_especial = $request->menu_especial_id;
        }

        $family->telefono = $request->telefono;

        $family->save();

        UserController::history('Editó el invitado del evento '. $family->estudiante->event->school->nombre . ' ' . $family->estudiante->event->curso . ' del día ' . $family->estudiante->event->fecha. '<br>' .
            'Versión anterior: <br>'.$beforeEditFamily.'<br>'.
            'Versión nueva: <br>'.

            'Invitado: ' . $family->nombre .'<br>'.
            'ID estudiante = ' . $family->estudiante_id. '<br>' .
            'ID menu especial: ' . $family->menu_especial. '<br>'.
            'ID menu especial 2: '. $family->menu_especial_2. '<br>'.
            'Teléfono: '. $family->telefono. '<br>'.
            'Tipo: '. $family->tipo. '<br>'
        );

        return back();
    }

    public function deleteStudent(Estudiante $student)
    {
        $student->delete();

        UserController::history('Eliminó el estudiante ' .$student->nombre. ', del evento '.$student->event->school->nombre . ' ' . $student->event->curso . ' del día ' . $student->event->fecha);

        return true;
    }

    public function payPartOfDebt(Estudiante $student, Request $request)
    {
        $pago = new Pago();
        $pago->estudiante_id = $student->id;
        $pago->amount = $request->pago;
        $pago->tipo = 'adelanto';
        $pago->save();

        UserController::history('Registró ADELANTO por $'.$pago->amount.' del estudiante ' .$student->nombre. ', del evento '.$student->event->school->nombre . ' ' . $student->event->curso . ' del día ' . $student->event->fecha);

        return back();
    }

    public function deleteAdvancedPayment(Pago $payment)
    {
        $payment->delete();

        UserController::history('Eliminó un pago adelantado del estudiante ' . $payment->estudiante->nombre);

        return back();
    }

    public function closePrice(Estudiante $estudiante)
    {
        $resumen = new EstudiantesResumen();
        $resumen->estudiante_id = $estudiante->id;
        $resumen->precio_unitario = $estudiante->event->menu->precio;
        $resumen->precio_unitario_descuentos = $estudiante->getPriceOfAdults() / (count($estudiante->people->where('tipo', 'adulto')->where('fuera_termino', 0)) + 1);
        $resumen->interes_cuotas = $estudiante->paymentType->interes;
        $resumen->descuento_cantidad_egresados = $estudiante->event->getEventDiscountByAmountOfStudents();
        $resumen->descuento_estudiante = $estudiante->descuento_especial;
        $resumen->descuento_dia_elegido = $estudiante->event->day->descuento;
        $resumen->iva = round(($estudiante->getPriceOfMinorsOfTwelve() + $estudiante->getPriceOfAdults()) * $estudiante->medioDePago->iva / 100);
        $resumen->total = round($estudiante->getTotalPrice());

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

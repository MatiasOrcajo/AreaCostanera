<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGraduatePartyRequest;
use App\Models\Constants;
use App\Models\Dia;
use App\Models\EgresadoDescuento;
use App\Models\Egresados;
use App\Models\Escuela;
use App\Models\Estudiante;
use App\Models\FormasPago;
use App\Models\MediosPago;
use App\Models\Menu;
use App\Models\MenuEspecial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class GraduatePartyController extends Controller
{

    public function index()
    {
        $graduateParties = Egresados::orderBy('fecha_carbon')
            ->get()
            ->map(function ($query) {
                $date = Carbon::parse($query->fecha);
                if ($date->add(24, 'hours') >= Carbon::now()->add(-3, 'hours'))
                    return $query;
            });

        $escuelas = Escuela::all();
        $dias = Dia::all();
        $menus = Menu::all();
        $formasPago = FormasPago::all();


        return view('dashboard', compact('graduateParties', 'escuelas', 'dias', 'menus', 'formasPago'));
    }


    /**
     * Create new graduate party
     * @param StoreGraduatePartyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function createGraduateParty(StoreGraduatePartyRequest $request)
    {
        $validated = $request->validated();
        $graduateDate = Carbon::createFromFormat('Y-m-d', $validated['fecha'])->format('d-m-Y');
//        $paymentDate = Carbon::createFromFormat('Y-m-d', $validated['fecha_pago'])->format('d-m-Y');

        $graduateParty = Egresados::create([
            'escuela_id' => $validated['escuela_id'],
            'curso' => $validated['curso'],
            'fecha' => $graduateDate,
            'fecha_carbon' => Carbon::parse($graduateDate),
            'cantidad_egresados' => $validated['cantidad_egresados'],
//            'fecha_pago' => $paymentDate,
            'dia_id' => $validated['dia_id'],
            'menu_id' => $validated['menu_id'],
            'slug' => Str::slug($validated['escuela_id'] . '-' . $validated['curso'] . '-' . $graduateDate),
//            'forma_pago_id' => $validated['forma_pago_id']
        ]);

        UserController::history('Creó la fiesta de egresados '. $graduateParty->school->nombre . ' ' . $graduateParty->curso . ' del día ' . $graduateDate);

        return back()->with('success', 'Fiesta añadida');
    }


    public function showGraduateParty($slug)
    {
        $event = Egresados::where('slug', $slug)->first();
        $graduates = $event->persons;
        $menus = Menu::all();
        $formasPago = FormasPago::all();
        $specialMenu = MenuEspecial::all();
        $mediosPago = MediosPago::all();
        $escuelas = Escuela::all();
        $dias = Dia::all();

        UserController::history('Ingresó a ver '. $event->school->nombre . ' ' . $event->curso . ' del día ' . $event->fecha);

        return view('showEvent', compact('event', 'menus', 'formasPago', 'specialMenu', 'graduates', 'mediosPago', 'escuelas', 'dias'));
    }

    public function listGraduatePartyPeople(int $id)
    {

        $data = Estudiante::where('egresado_id', $id)->with(['menu', 'paymentType', 'people'])
            ->get()
            ->map(function ($query) {
                return [
                    'id' => $query->id,
                    'nombre' => $query->nombre,
                    'menu' => $query->medioDePago->metodo,
                    'personas' => count($query->people),
                    'menu_especial' => $query->menu_especial_id ? MenuEspecial::find($query->menu_especial_id)
                        ->nombre : '-',
                    'menu_especial_2' => $query->menu_especial_2_id ? MenuEspecial::find($query->menu_especial_2_id)
                        ->nombre : '-',
                    'fecha_pago' => \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $query->fecha_pago)->format('d-m-Y'),
                    'forma_pago' => $query->paymentType->nombre,
                    'email' => $query->email,
                    'telefono' => $query->telefono,
                    'observaciones' => $query->observaciones,
                    'total' => '$' . $query->getTotalPrice()
                ];
            });

        return DataTables::of($data)->make(true);
    }

    public function deleteEvent(Egresados $event)
    {
        UserController::history('Eliminó el evento '. $event->school->nombre . ' ' . $event->curso . ' del día ' . $event->fecha);

        $event->delete();

        return true;
    }

    public function edit(Request $request, Egresados $event)
    {
        $beforeEditEvent = 'Escuela: '. $event->school->nombre. '<br>'.
            'Menu: ' . $event->menu->nombre. '<br>'.
            'Descuento día: ' . $event->day->nombre. '<br>'.
            'Curso: ' . $event->curso. '<br>'.
            'Cantidad de egresados: ' . $event->cantidad_egresados. '<br>'.
            'Fecha: ' . $event->fecha. '<br>';

        $event->update($request->toArray());

        UserController::history('Editó la fiesta de egresados '. $event->school->nombre . ' ' . $event->curso . ' del día ' . $event->fecha. '<br> Version previa: <br>' . $beforeEditEvent. '<br>' .
            'Versión nueva: <br>' .
            $event->school->nombre. '<br>'.
            'Menu: ' . $event->menu->nombre. '<br>'.
            'Descuento día: ' . $event->day->nombre. '<br>'.
            'Curso: ' . $event->curso. '<br>'.
            'Cantidad de egresados: ' . $event->cantidad_egresados. '<br>'.
            'Fecha: ' . $event->fecha. '<br>'

        );

        return back();
    }

    public function finishedEvents()
    {
        return view('finishedEvents');
    }

    public function listFinishedEvents()
    {
        $data = Egresados::where('fecha_carbon', '<', Carbon::now())->get()->map(function ($query) {
            return [
                "evento" => 'Escuela ' . Escuela::find($query->escuela_id)->nombre . ', curso ' . $query->curso,
                "fecha" => $query->fecha,
                "slug" => $query->slug
            ];
        });

        return DataTables::of($data)->make(true);
    }

    public function createDiscount(Request $request, Egresados $event)
    {
        $discount = new EgresadoDescuento();
        $discount->descuento = $request->descuento;
        $discount->egresado_id = $event->id;

        $discount->save();

        UserController::history('Creó el descuento ' . $discount->descuento);

        return back();
    }

    public function editDiscount(Request $request, Egresados $event)
    {
        $beforeEditDiscount =    'Evento: ' . $event->school->nombre . ' ' . $event->curso . ' del día ' .                       $event->fecha.
                                'ID descuento = ' . $event->id. '<br>' .
                                'Descuento: ' . $event->discount->descuento. '<br>'
                                ;

        $event->discount->descuento = $request->descuento;
        $event->discount->save();

        UserController::history('Editó el descuento del evento '. $event->school->nombre . ' ' . $event->curso . ' del día ' . $event->fecha. '<br>' .
            'Versión anterior: <br>'.$beforeEditDiscount.'<br>'.
            'Versión nueva: <br>'.  'Evento: ' . $event->school->nombre . ' ' . $event->curso . ' del día ' .$event->fecha.
                                    'ID descuento = ' . $event->id. '<br>' .
                                    'Descuento: ' . $event->discount->descuento. '<br>'

        );

        return back();
    }

    public function showStudentsList(Egresados $event)
    {
        return view('showEventStudentsList', compact('event'));
    }

    public function listStudentsTable(Egresados $event)
    {
        $data = $event->persons->map(function ($query) {
            return [
                "nombre" => $query->nombre
            ];
        });

        return DataTables::of($data)->make(true);
    }

    public function listGuestsTable(Egresados $event)
    {
        $data = $event->invited->map(function ($query) {
            return [
                "nombre" => $query->nombre,
                "telefono" => $query->telefono
            ];
        });

        return DataTables::of($data)->make(true);
    }

    public function listMenusTable(Egresados $event)
    {
        $data = $event->invited->whereNotNull('menu_especial')->map(function ($query) {
            return [
                "nombre" => $query->nombre,
                "menu" => MenuEspecial::find($query->menu_especial)
                    ->nombre
            ];
        });

        $data = $data->concat($event->persons->whereNotNull('menu_especial_id')->map(function ($query) {
            return [
                "nombre" => $query->nombre,
                "menu" => MenuEspecial::find($query->menu_especial_id)->nombre
            ];
        }));

        return DataTables::of($data)->make(true);
    }

    public function eventDebtors(Egresados $event)
    {
        $debtors = $event->installmentsForThisEvent->where('status', 0)->where('fecha_estipulada', '<', Carbon::now());
        $data = $debtors->map(function($query){
            $student = Estudiante::find($query->estudiante_id);
            $first = round(($student->getTotalPriceWithAdvancePayments() - $student->getDuesPayedAmount()) / $student->getRemainingDuesCount());
            +
            $second = round(($student->getTotalPriceWithAdvancePayments() - $student->getDuesPayedAmount()) / $student->getRemainingDuesCount()) * \App\Models\InteresCuota::first()->interes/ 100;

           return[
               'id' => Estudiante::find($query->estudiante_id)->id,
               'nombre' => Estudiante::find($query->estudiante_id)->nombre,
               'fecha_estipulada' => Carbon::parse($query->fecha_estipulada)->format('d-m-Y'),
               'monto' => $first == 0 ? '-' : '$'.$first . ' + interes de $'.$second
           ] ;
        });

        return DataTables::of($data)->make(true);
    }

}

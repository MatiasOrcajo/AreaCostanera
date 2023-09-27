<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Egresados;
use App\Models\Escuela;
use App\Models\Estudiante;
use App\Models\MediosPago;
use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReportsController extends Controller
{
    public function indexEvents()
    {
        return view('reports.events');
    }

    public function indexEventsForAdminOnly()
    {
        return view('reports.eventsForAdminOnly');
    }

    public function indexSeasons()
    {
        return view('reports.seasons');
    }

    public function eventsList()
    {
        $data = Egresados::all()->map(function ($query) {
            return [
                'nombre' => Escuela::find($query->escuela_id)->nombre . ' dia ' . $query->fecha,
                'id' => $query->id
            ];
        });

        return DataTables::of($data)->make(true);
    }

    public function showEventReport(Egresados $event)
    {
        $electronicsPayments = 0;
        $cashPayments = 0;

        $event->payments()->map(function ($student) use (&$electronicsPayments, &$cashPayments) {
            foreach ($student->payments as $payment) {
                if ($student->medio_pago_id == 2) {
                    return $cashPayments += $payment->amount;
                } else {
                    return $electronicsPayments += $payment->amount;
                }
            }
        });

        return view('reports.event', compact('event', 'cashPayments', 'electronicsPayments'));
    }

    public function listPaymentsForEvent(Egresados $event)
    {
        $data = [];
        $students = $event->payments();
        $students->map(function ($student) use (&$data) {
            foreach ($student->payments as $payment) {
                return $data[] = [
                    "nombre" => $student->nombre,
                    "monto" => "$" . $payment->amount,
                    "tipo" => strtoupper($payment->tipo),
                    "medio" => strtoupper(MediosPago::find($student->medio_pago_id)->metodo),
                    "fecha" => Carbon::parse($payment->created_at)->format('d-m-Y')
                ];
            }
        });

        return DataTables::of($data)->make(true);
    }

    public function searchByDates(Request $request)
    {
        $payments = Pago::whereBetween('created_at', [$request->first_date, $request->second_date])->get();

        $data = [
            'electronicsPayments' => 0,
            'cashPayments' => 0
        ];

        foreach ($payments as $payment) {
            $student = Estudiante::find($payment->estudiante_id);
            if ($student->medio_pago_id == 2) {
                $data['cashPayments'] += $payment->amount;
            } else {
                $data['electronicsPayments'] += $payment->amount;
            }
        }

        return json_encode($data);
    }

    public function listPaymentsByDates(Request $request)
    {
        $data = isset($request->first_date) ? Pago::whereBetween('created_at', [$request->first_date, $request->second_date])->get() : Pago::whereDate('created_at', $request->date)->get();

        $data = $data->map(function ($query) {
            $student = Estudiante::find($query->estudiante_id);
            return [
                "nombre" => $student->nombre,
                "monto" => '$'.$query->amount,
                "tipo" => strtoupper($query->tipo),
                "medio" => strtoupper(MediosPago::find($student->medio_pago_id)->metodo),
                "fecha" => Carbon::parse($query->created_at)->format('d-m-Y')
            ];
        });


        return DataTables::of($data)->make(true);
    }

    public function indexChargesPerDay()
    {
        return view('reports.day');
    }

    public function getChargesPerDay(Request $request)
    {
        $payments = Pago::whereDate('created_at', $request->date)->get();

        $data = [
            'electronicsPayments' => 0,
            'cashPayments' => 0
        ];

        foreach ($payments as $payment) {
            $student = Estudiante::find($payment->estudiante_id);
            if ($student->medio_pago_id == 2) {
                $data['cashPayments'] += $payment->amount;
            } else {
                $data['electronicsPayments'] += $payment->amount;
            }
        }

        return json_encode($data);
    }
}

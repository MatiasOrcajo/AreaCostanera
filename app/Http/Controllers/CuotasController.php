<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use App\Models\EstudianteCuota;
use App\Models\InteresCuota;
use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CuotasController extends Controller
{
    public function getDueStatus(EstudianteCuota $cuota)
    {
        return $cuota->status;
    }

    public function payDue(EstudianteCuota $cuota)
    {
        if ($cuota->status == 1) {
            $cuota->status = 0;
            Pago::where('estudiantes_cuotas_id', $cuota->id)->delete();
        } else {
            $cuota->status = 1;
            $cuota->fecha_pago = Carbon::now();
            $pago = new Pago();
            $pago->estudiante_id = $cuota->estudiante_id;
            $pago->estudiantes_cuotas_id = $cuota->id;
            $estudiante = Estudiante::find($cuota->estudiante_id);

            if (Carbon::now() > Carbon::parse($cuota->fecha_estipulada)) {
                $pago->amount = round(
                    ($estudiante->getTotalPriceWithAdvancePayments() / count($estudiante->cuotas)));
                $pago->interes = (($estudiante->getTotalPriceWithAdvancePayments() / count($estudiante->cuotas) * InteresCuota::first()->interes / 100));
            } else {
                $pago->amount = round(
                    (($estudiante->getTotalPriceWithAdvancePayments() - $estudiante->getDuesPayedAmount()) / $estudiante->getRemainingDuesCount()));
            }

            $pago->tipo = 'cuota';
            $pago->save();
        }
        $cuota->save();

        return true;
    }
}

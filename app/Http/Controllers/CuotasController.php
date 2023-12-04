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
        //Si la cuota existe y está saldada, se marca como no saldada
        if ($cuota->status == 1) {
            $cuota->status = 0;
            Pago::where('estudiantes_cuotas_id', $cuota->id)->delete();

            UserController::history('Marcó como NO SALDADO el pago de la cuota ID '.$cuota->id.', de monto $'.$cuota->amount.', del estudiante '. $cuota->estudiante->nombre);

        } else {
            //Se marca la cuota como saldada
            $cuota->status = 1;
            $cuota->fecha_pago = Carbon::now();

            UserController::history('Marcó como SALDADO el pago de la cuota ID '.$cuota->id.', de monto $'.$cuota->amount.', del estudiante '. $cuota->estudiante->nombre);

            //Se registra el pago
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

            UserController::history('Registró el pago ID '.$pago->id.' de monto $'.$pago->amount);
        }

        $cuota->save();

        return true;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $table = 'estudiantes';
    protected $guarded;
    use HasFactory;

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function paymentType()
    {
        return $this->belongsTo(FormasPago::class, 'forma_pago_id');
    }

    public function event()
    {
        return $this->belongsTo(Egresados::class, 'egresado_id');
    }

    public function specialMenu()
    {
        return $this->belongsTo(MenuEspecial::class, 'menu_especial');
    }

    public function people()
    {
        return $this->hasMany(EstudianteFamiliares::class, 'estudiante_id');
    }

    public function medioDePago()
    {
        return $this->belongsTo(MediosPago::class, 'medio_pago_id');
    }

    public function getPriceOfAdults()
    {
        $adultsCount = count($this->people->where('tipo', 'adulto')) + 1;
        $menuPrice = $this->resumen ? $this->resumen->precio_unitario : Egresados::find($this->egresado_id)->menu->precio;

        return $adultsCount * $menuPrice - ($adultsCount * $menuPrice) * $this->getTotalDiscounts() / 100;
    }

    public function getPriceOfMinorsOfTwelve()
    {
        $minorsCount = count($this->people->where('tipo', 'menor_12'));
        $menuPrice = $this->resumen ? $this->resumen->precio_unitario : Egresados::find($this->egresado_id)->menu->precio;

        return $minorsCount * $menuPrice / 2 - ($minorsCount * $menuPrice / 2) * $this->getTotalDiscounts() / 100;
    }

    public function payments()
    {
        return $this->hasMany(Pago::class, 'estudiante_id');
    }

    public function getRemainingDuesCount()
    {
        return count($this->cuotas) - count($this->cuotas->where('status', 1));
    }

    public function getTotalDiscounts()
    {
        $special_discount = $this->event->discount->descuento ?? 0;

        return Egresados::find($this->egresado_id)->getEventDiscountByAmountOfStudents() + $this->event->day->descuento + $special_discount + $this->descuento_especial;
    }

    public function getTotalPrice()
    {
        if ($this->resumen == null) {
            $total_adultos_egresado = $this->getPriceOfMinorsOfTwelve() + $this->getPriceOfAdults();

            $total =
                $total_adultos_egresado
                + ($total_adultos_egresado * $this->paymentType->interes / 100);

            $total += $total * $this->medioDePago->iva / 100;

        } else {
//            $invitados_fuera_termino = array_sum($this->people->where('fuera_termino', 1)->pluck('total')->toArray());
            $total = $this->resumen->total;
        }

        return round($total);

    }

    public function getDuesPayedAmount()
    {
        $payedDues = $this->cuotas->where('status', 1)->pluck('id')->map(function ($query) {
            return Pago::where('estudiantes_cuotas_id', $query)->pluck('amount')->first();
        });

        return array_sum($payedDues->toArray());
    }

    public function getTotalPriceWithAdvancePayments()
    {
        return round($this->getTotalPrice() - array_sum($this->payments->where('tipo', 'adelanto')->pluck('amount')->toArray()));
    }

    public function calculateDuesAmount()
    {
        $total = $this->getTotalPriceWithAdvancePayments();
        return $total / count($this->cuotas);
    }

    public function cuotas()
    {
        return $this->hasMany(EstudianteCuota::class, 'estudiante_id');
    }

    public function resumen()
    {
        return $this->hasOne(EstudiantesResumen::class, 'estudiante_id');
    }
}

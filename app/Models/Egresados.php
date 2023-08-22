<?php

namespace App\Models;

use App\Http\Requests\StoreGraduatePartyRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Egresados extends Model
{
    protected $guarded;
    protected $table = 'egresados';
    use HasFactory;


    /**
     * Get the color reference for difference between now and event date
     * @return string
     *
     */
    public function getDateStatusCss()
    {
        $graduationDate = Carbon::parse($this->fecha);
        $now = Carbon::now();
        $diff = $graduationDate->diffInDays($now) + 1;

        switch ($diff) {

            case $diff <= 14:
                $css = "background: rgb(219,37,24);
                        background: linear-gradient(180deg, rgba(219,37,24,1.2) 35%, rgba(182,2,2,1.2) 70%);";
                break;
            case $diff > 14 && $diff < 30:
                $css = "background: rgb(239,237,13);
                        background: linear-gradient(180deg, rgba(239,237,13,1.2) 35%, rgba(210,201,20,1.2) 70%);";
                break;
            default:
                $css = "background: rgb(21,196,35);
                        background: linear-gradient(180deg, rgba(21,196,35,1.2) 35%, rgba(15,140,17,1.2) 70%);";
                break;

        }

        return $css;
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(Escuela::class, 'escuela_id');
    }

    public function persons(): HasMany
    {
        return $this->hasMany(Estudiante::class, 'egresado_id');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function day()
    {
        return $this->belongsTo(Dia::class, 'dia_id');
    }

    public function getEventDiscountByAmountOfStudents()
    {
        $numberOfStudents = $this->cantidad_egresados;
        $descuento = DescuentosCantidadegresados::first();

        switch ($numberOfStudents){
            case $numberOfStudents >= 20 && $numberOfStudents <= 30:
                $descuento = $descuento->descuento_20_a_30;
                break;
            case $numberOfStudents >= 31 && $numberOfStudents <= 40:
                $descuento = $descuento->descuento_31_a_50;
                break;
            case $numberOfStudents >= 41 && $numberOfStudents <= 60:
                $descuento = $descuento->descuento_51_a_70;
                break;
            case $numberOfStudents >= 61 && $numberOfStudents <= 75:
                $descuento = $descuento->descuento_71_a_100;
                break;
            case $numberOfStudents >= 76 && $numberOfStudents <= 100:
                $descuento = $descuento->descuento_101_a_150;
                break;
            case $numberOfStudents >= 100:
                $descuento = $descuento->descuento_151_o_mas;
                break;
            default:
                $descuento = 0;
                break;
        }

        return $descuento;
    }

    public function discount()
    {
        return $this->hasOne(EgresadoDescuento::class, 'egresado_id');
    }

    public function invited(): HasManyThrough
    {
        return $this->hasManyThrough(EstudianteFamiliares::class, Estudiante::class, 'egresado_id', 'estudiante_id')->orderBy('nombre');
    }

    /**
     * Returns all student that have unpaid installments
     */
    public function installmentsForThisEvent()
    {
        return $this->hasManyThrough(EstudianteCuota::class, Estudiante::class, 'egresado_id', 'estudiante_id')->orderBy('fecha_estipulada');
    }

    /**
     * Returns payments made by event's students
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|HasMany[]
     */
    public function payments()
    {
        return $this->persons()->whereHas('payments')->with('payments')->get();
    }

    public function electronicsPayments()
    {

    }

}

<?php

namespace App\Models;

use App\Http\Requests\StoreGraduatePartyRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
//        dump($diff);
        switch ($diff) {

            case $diff <= 14:
                return "background: rgb(219,37,24);
                        background: linear-gradient(180deg, rgba(219,37,24,1.2) 35%, rgba(182,2,2,1.2) 70%);";
            case $diff > 14 && $diff < 30:
                return "background: rgb(239,237,13);
                        background: linear-gradient(180deg, rgba(239,237,13,1.2) 35%, rgba(210,201,20,1.2) 70%);";
            default:
                return "background: rgb(21,196,35);
                        background: linear-gradient(180deg, rgba(21,196,35,1.2) 35%, rgba(15,140,17,1.2) 70%);";

        }
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

    public function getEventDiscountByAmountOfStudents()
    {
        $numberOfStudents = $this->cantidad_egresados;
        $descuento = DescuentosCantidadegresados::first();

        switch ($numberOfStudents){
            case $numberOfStudents >= 20 && $numberOfStudents <= 30:
                $descuento = $descuento->descuento_20_a_30;
                break;
            case $numberOfStudents >= 31 && $numberOfStudents <= 50:
                $descuento = $descuento->descuento_31_a_50;
                break;
            case $numberOfStudents >= 51 && $numberOfStudents <= 70:
                $descuento = $descuento->descuento_51_a_70;
                break;
            case $numberOfStudents >= 71 && $numberOfStudents <= 100:
                $descuento = $descuento->descuento_71_a_100;
                break;
            case $numberOfStudents >= 101 && $numberOfStudents <= 150:
                $descuento = $descuento->descuento_101_a_150;
                break;
            case $numberOfStudents >= 151:
                $descuento = $descuento->descuento_151_o_mas;
                break;
        }

        return $descuento;
    }

    public function getEventDiscountByDays(int $dia_id, $quantity)
    {
        $dia_descuento = DiasDescuentos::where('dia_id', $dia_id)->first();

        switch ($quantity){
            case $quantity >= 20 && $quantity <= 30:
                $descuento = $dia_descuento->descuento_20_a_30;
                break;
            case $quantity >= 31 && $quantity <= 50:
                $descuento = $dia_descuento->descuento_31_a_50;
                break;
            case $quantity >= 51 && $quantity <= 70:
                $descuento = $dia_descuento->descuento_51_a_70;
                break;
            case $quantity >= 71 && $quantity <= 100:
                $descuento = $dia_descuento->descuento_71_a_100;
                break;
            case $quantity >= 101 && $quantity <= 150:
                $descuento = $dia_descuento->descuento_101_a_150;
                break;
            case $quantity >= 151:
                $descuento = $dia_descuento->descuento_151_o_mas;
                break;
        }

        return $descuento;
    }

    public function discount()
    {
        return $this->hasOne(EgresadoDescuento::class, 'egresado_id');
    }

}

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


}

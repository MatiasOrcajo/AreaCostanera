<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escuela extends Model
{
    protected $guarded;
    protected $table = 'escuelas';
    use HasFactory;

    /**
     * Return school events
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     */
    public function events(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Egresados::class, 'escuela_id');
    }
}

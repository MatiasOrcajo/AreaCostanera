<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Constants extends Model
{
    use HasFactory;

    const GRADUATE_PARTY_STATUS_ACTIVE = 1;
    const GRADUATE_PARTY_STATUS_UNACTIVE = 2;

    /**
     * APPLICATIONS
     */

    const MENU = 1;


    /**
     * MENU STATUS
     */

    const ACTIVE    = 1;
    const INACTIVE  = 0;

    const MENU_STATUS = [
        self::ACTIVE    => 'ACTIVO',
        self::INACTIVE  => 'INACTIVO'
    ];
}

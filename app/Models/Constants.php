<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Constants extends Model
{
    use HasFactory;

    const GRADUATE_PARTY_STATUS_ACTIVE = 1;
    const GRADUATE_PARTY_STATUS_UNACTIVE = 2;
}

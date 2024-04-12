<?php

namespace App\Models;

use App\Traits\PartyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialEvent extends Model
{
    use HasFactory;
    use PartyTrait;

}

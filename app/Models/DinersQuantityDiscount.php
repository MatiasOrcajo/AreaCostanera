<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DinersQuantityDiscount extends Model
{
    use HasFactory;

    protected $table = 'diners_quantity_discounts';
    protected $guarded;

}

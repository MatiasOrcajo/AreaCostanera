<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $guarded;

    public function historyRecords()
    {
        return $this->hasMany(HistoryRecord::class, 'specific_id')->where('application', Constants::MENU);
    }
}

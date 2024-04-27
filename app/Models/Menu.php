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

    /**
     * @param String $category "SOCIAL" or "EGRESADOS"
     * @return Menu[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getListOfSpecificMenuCategory(String $category)
    {
        return Menu::all()->filter(function ($menu) use($category){
            $name = explode(' ', $menu->nombre);
            if(in_array($category, $name)) return $menu;
        });
    }


    public function socialEvents()
    {
        return $this->hasMany(SocialEvent::class, 'menu_id');
    }
}

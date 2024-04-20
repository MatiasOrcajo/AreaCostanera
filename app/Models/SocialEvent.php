<?php

namespace App\Models;

use App\Traits\PartyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialEvent extends Model
{
    use HasFactory;
    use PartyTrait;

    protected $guarded;

    /**
     * Returns all payment of social event
     */
    public function payments()
    {
        return $this->hasMany(EventPayment::class, 'social_event_id');
    }

    /**
     * Updates total when payment is done.
     * @param $quantity -> quantity of diners
     */
    public function updateTotalForPayment($quantity): void
    {
        $this->total = $this->total - ($quantity * $this->menu->precio);
        $this->save();

        if($this->total <= 0)
        {
            $this->setStatusToNotActive();
        }
    }

    /**
     * Updates total when discount is created or edited
     * @return void
     */
    public function updateTotalForDiscount()
    {
        $originalPrice = $this->diners * $this->menu->precio;
        $this->total = ($originalPrice) - (($originalPrice * $this->discount) / 100);
        $this->save();
    }

    /**
     * Updates total when dish price is updated
     * @return void
     */
    public function updateTotalForNewDishPrice(): void
    {
        $payedDishes = array_sum($this->payments->pluck('diners_quantity')->toArray());
        $this->total = $this->total + (($this->diners - $payedDishes) * $this->menu->precio);
        $this->save();
    }

    /**
     * Set status to not active when event is fully pay
     * or when current date > event day
     * @return void
     */
    public function setStatusToNotActive(): void
    {
        $this->status = 0;
        $this->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}

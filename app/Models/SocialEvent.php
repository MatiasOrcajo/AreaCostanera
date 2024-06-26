<?php

namespace App\Models;

use App\Traits\PartyTrait;
use Carbon\Carbon;
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
     * Get amount of money of payed dishes
     * @return float|int
     */
    public function getAmountOfPayments()
    {
        return array_sum($this->payments->pluck('payment')->toArray());
    }

    /**
     * Updates total when payment is done.
     * @param $quantity -> quantity of diners
     */
    public function updateTotalForPayment($quantity): void
    {
        $this->total = $this->total - ($quantity * $this->returnMenuPriceWithDiscounts());
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
        $originalPrice = $this->diners * $this->returnMenuPriceWithDiscounts();
        $this->total = ($originalPrice) - (($originalPrice * $this->discount) / 100);
        $this->save();
    }

    /**
     * @param $oldDinersValue
     * @return void
     */
    public function updateTotalWhenEventIsEdited($oldDinersValue)
    {
        $dinersAlreadyPaid = $this->getAmountOfPayments();
        $this->total = ($this->diners * $this->returnMenuPriceWithDiscounts()) - $dinersAlreadyPaid;
//        $diferencia = $this->diners - $oldDinersValue;
//        $this->total = $this->total + $diferencia;
        $this->save();
    }

    /**
     * Updates total when dish price is updated
     * @return void
     */
    public function updateTotalForNewDishPrice(): void
    {
        $payedDishes = $this->getCountOfPayedDishes();
        $dishesDiff = $this->diners - $payedDishes;
        $newTotal = $this->returnMenuPriceWithDiscounts() * $dishesDiff;

        $this->total = $newTotal;
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

    public function getFormatedDate()
    {
        return Carbon::parse($this->fecha)->format('d-m-Y');
    }

    public function getCountOfPayedDishes()
    {
        return array_sum($this->payments->pluck('diners_quantity')->toArray());
    }


    /**
     * Get discount by q of diners
     * @return DinersQuantityDiscount[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getDiscountByDiners()
    {
        $discount = 0;

        DinersQuantityDiscount::all()
            ->map(function ($query) use(&$discount){
               if($this->diners >= $query->from && $this->diners <= $query->to)
                   $discount = $query->discount;
            });

        return $discount;
    }

    public function returnMenuPriceWithDiscounts()
    {
        return $this->menu->precio - (($this->menu->precio * $this->getDiscountByDiners()) / 100);
    }

}

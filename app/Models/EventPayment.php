<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPayment extends Model
{
    protected $table = 'events_payments';
    use HasFactory;

    /**
     * Return associated event
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(SocialEvent::class, 'social_event_id');
    }

    /**
     * Soft delete of a payment, updating the event total
     * @return void
     */
    public function softDelete()
    {
        $this->event->total += $this->payment;
        $this->event->save();
        $this->delete();
    }
}

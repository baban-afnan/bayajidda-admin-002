<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CableSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_ref',
        'cablename',
        'cableplan',
        'smart_card_number',
        'amount',
        'status'
    ];

    /**
     * Get the user associated with the cable subscription.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

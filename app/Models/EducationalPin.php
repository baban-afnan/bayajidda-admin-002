<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationalPin extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_ref',
        'exam_name',
        'quantity',
        'pins',
        'amount',
        'status'
    ];

    /**
     * Get the user associated with the educational pin.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    protected $fillable = [
        'payment_id',
        'user_id',
        'amount',
        'amount_paid',
        'snap_token',
        'status',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookedService extends Model
{
    protected $fillable = [
        'booking_id',
        'service_id',
        'service_schedule_id',
        'price',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function serviceSchedule()
    {
        return $this->belongsTo(ServiceSchedule::class, 'service_schedule_id');
    }
}

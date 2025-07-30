<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSchedule extends Model
{
    protected $fillable = [
        'service_id',
        'start_datetime',
        'end_datetime',
        'status',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function bookedService()
    {
        return $this->hasOne(BookedService::class, 'service_schedule_id');
    }

    public function getScheduleDateString()
    {
        // Format if it's a single day
        if ($this->start_datetime->isSameDay($this->end_datetime)) {
            return $this->start_datetime->translatedFormat('l, d F Y, H:i') . ' - ' . $this->end_datetime->translatedFormat('H:i');
        }

        return $this->start_datetime->translatedFormat('l, d F Y, H:i') . ' - ' . $this->end_datetime->translatedFormat('l, d F Y, H:i');
    }
}

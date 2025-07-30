<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldSchedule extends Model
{
    protected $fillable = [
        'field_id',
        'start_datetime',
        'end_datetime',
        'status',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function booking()
    {
        return $this->hasOne(Booking::class);
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

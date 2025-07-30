<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchPhoto extends Model
{
    protected $fillable = ['booking_id', 'path', 'name'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function getPathAttribute($value)
    {
        return $this->attributes['path'] ? get_image_url($value) : null;
    }
}

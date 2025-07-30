<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'user_id',
        'price_per_hour',
        'type',
        'description',
        'portfolio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(ServiceSchedule::class);
    }

    // public function getImageUrl()
    // {
    //     return get_image_url($this->portfolio, 'https://placehold.co/300x400?text=Service+Image');
    // }

    public function getTypeLabel()
    {
        return match ($this->user->role) {
            'photographer' => 'Fotografer',
            'referee' => 'Wasit',
            default => 'Unknown',
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Field extends Model
{
    protected $fillable = [
        'name',
        'location',
        'price_per_hour',
        'availability',
        'description',
        'manager_id',
        'image',
        'village_id',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function schedules()
    {
        return $this->hasMany(FieldSchedule::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facilities::class, 'field_facility', 'field_id', 'facility_id');
    }

    public function getImageUrlAttribute()
    {
        return $this->image && Storage::disk('public')->exists($this->image)
            ? Storage::url($this->image)
            : asset('assets/images/example-lapangan-1.webp');
    }

    // Data Fetching Methods
    public static function getFieldData()
    {
        $user = auth()->user();

        $query = self::with('manager', 'schedules');

        if ($user->role !== 'super_admin') {
            $query->where('manager_id', $user->id);
        }

        return $query->latest()->get();
    }

    public function getFullLocationAttribute()
    {
        return $this->village ? ucwords(strtolower($this->village->district->name)) . ", " . ucwords(strtolower($this->village->name)). ", " . $this->location : $this->location;
    }
}

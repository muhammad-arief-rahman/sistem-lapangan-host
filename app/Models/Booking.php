<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'field_id',
        'field_schedule_id',
        'type',
        'status',
        'match_photo_link',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function openMatch()
    {
        return $this->hasOne(OpenMatch::class);
    }

    public function trofeo()
    {
        return $this->hasOne(Trofeo::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function fieldSchedule()
    {
        return $this->belongsTo(FieldSchedule::class);
    }

    public function bookedServices()
    {
        return $this->hasMany(BookedService::class, 'booking_id', 'id');
    }

    public function photos()
    {
        return $this->hasMany(MatchPhoto::class, 'booking_id', 'id');
    }

    public function getTypeLabel()
    {
        return match ($this->type) {
            'regular' => 'Booking Lapangan',
            'open_match' => 'Open Match',
            'trofeo' => 'Trofeo',
            default => 'Unknown',
        };
    }

    public function getBookingDateString()
    {
        return $this->created_at->translatedFormat('l, d F Y, H:i');
    }

    public function getPerPersonPrice()
    {
        if ($this->type === 'open_match') {
            return $this->openMatch->registration_fee ?? 0;
        }

        if ($this->type === 'trofeo') {
            return $this->trofeo->registration_fee ?? 0;
        }

        return $this->payment->total_amount;
    }

    public function getStatusAttribute()
    {
        // Return finished if the booking is completed and past the field schedule time
        if ($this->fieldSchedule && $this->fieldSchedule->status === 'finished' && $this->fieldSchedule->end_datetime < now()) {
            return 'finished';
        }

        return $this->attributes['status'] ?? 'pending';
    }

    // Data Fetching Methods
    public static function getBookingData()
    {
        $user = auth()->user();
        $query = self::with('user', 'payment', 'field', 'fieldSchedule', 'bookedServices', 'bookedServices.service', 'bookedServices.service.user');

        if ($user->role === 'field_manager') {
            $query->whereHas('field', function ($q) use ($user) {
                $q->where('manager_id', $user->id);
            });
        } else if ($user->role !== 'super_admin') {
            $query->where('user_id', $user->id);
        }
        return $query->latest()->get();
    }
}

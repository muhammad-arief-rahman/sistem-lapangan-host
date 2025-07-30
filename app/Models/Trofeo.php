<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trofeo extends Model
{
    protected $fillable = [
        'booking_id',
        'match_name',
        'description',
        'registration_fee',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function matchups()
    {
        return $this->hasMany(TrofeoMatchup::class);
    }

    public function isValidTrofeo()
    {
        // Check if there are 2 trofeo matchups
        return $this->matchups()->count() === 2;
    }

    public function getPlayerString()
    {
        $creator = $this->booking->user->name ?? $this->booking->user->email;
        $isCreator = auth()->check() && auth()->id() === $this->booking->user_id;

        if ($isCreator) {
            $creator .= ' (Anda)';
        }

        if ($this->matchups->count() === 0) {
            return $creator;
        }

        return $creator . ', ' . $this->matchups->map(function ($matchup) {
            return $matchup->user->name ?? $matchup->user->email;
        })->implode(', dan ');
    }

    // Data Fetching Methods
    public static function getTrofeoData()
    {
        $user = auth()->user();
        $trofeoQuery = Trofeo::with('booking', 'booking.user');

        if ($user->role === 'community') {
            // Get if they are joining a trofeo
            $trofeoQuery->whereHas('matchups', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });

            // Get if they are the creator of a trofeo
            $trofeoQuery->orWhereHas('booking', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        return $trofeoQuery->latest()->get();
    }
}

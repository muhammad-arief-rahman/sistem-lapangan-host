<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpenMatch extends Model
{
    protected $fillable = [
        'order_id',
        'match_name',
        'description',
        'opponent_id',
        'registration_fee',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function getPlayerString()
    {
        $opponent = User::find($this->opponent_id);
        $creator = $this->booking->user->name ?? $this->booking->user->email;
        $isCreator = auth()->check() && auth()->id() === $this->booking->user_id;

        if ($isCreator) {
            $creator .= ' (Anda)';
        }

        return $creator . ($opponent ? ' vs ' . $opponent->name : '');
    }

    // Data Fetching Methods
    public static function getOpenMatchData()
    {
        $user = auth()->user();
        $openMatchQuery = self::with('booking', 'booking.user');

        if ($user->role === 'community') {
            // Get if they are joining an open match
            $openMatchQuery->where('opponent_id', $user->id);

            // Get if they are the creator of an open match
            $openMatchQuery->orWhereHas('booking', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        return $openMatchQuery->latest()->get();
    }
}

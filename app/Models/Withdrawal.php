<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'account_type',
        'account_number',
        'account_name',
        'status',
        'transfer_proof',
        'notes',
        'approved_at',
        'description',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusName()
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'completed' => 'Selesai',
            'failed' => 'Gagal',
            default => 'Tidak Diketahui',
        };
    }

    public function getImageUrl()
    {
        return get_image_url($this->transfer_proof, 'https://placehold.co/1600x900?text=Transfer+Proof');

    }

    // Data Fetching Methods
    public static function getWithdrawalData()
    {
        $user = auth()->user();

        $withdrawalQuery = Withdrawal::with('user');

        if ($user->role !== "super_admin") {
            $withdrawalQuery->where('user_id', $user->id);
        }

        $withdrawals = $withdrawalQuery->latest()->get();

        return $withdrawals;
    }
}

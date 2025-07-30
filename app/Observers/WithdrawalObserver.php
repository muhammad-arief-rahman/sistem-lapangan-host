<?php

namespace App\Observers;

use App\Models\Withdrawal;

class WithdrawalObserver
{
    public function created(Withdrawal $withdrawal)
    {
        // Create a mutation for the withdrawal
        if ($withdrawal->status === 'pending') {
            $mutation = $withdrawal->user->mutations()->create([
                'source' => 'Penarikan Dana',
                'amount' => -$withdrawal->amount,
                'description' => $withdrawal->description ?: 'Penarikan dana oleh pengguna',
            ]);
        }
    }

    public function updated(Withdrawal $withdrawal)
    {
        // Handle status changes without creating duplicate mutations
        if ($withdrawal->wasChanged('status')) {
            $originalStatus = $withdrawal->getOriginal('status');
            $newStatus = $withdrawal->status;

            // If withdrawal was rejected, refund the amount
            if ($originalStatus === 'pending' && $newStatus === 'rejected') {
                $withdrawal->user->mutations()->create([
                    'source' => 'Pengembalian Penarikan',
                    'amount' => $withdrawal->amount,
                    'description' => 'Pengembalian penarikan yang ditolak #' . $withdrawal->id,
                ]);
            }

            // If withdrawal was approved, no additional action needed
            // (the money was already deducted when created)
        }
    }

    public function beforeDeleted(Withdrawal $withdrawal)
    {
        // Create a mutation for the deletion of the withdrawal
        $mutation = $withdrawal->user->mutations()->create([
            'source' => 'Pembatalan Penarikan Dana',
            'amount' => $withdrawal->amount, // Revert the amount
            'description' => 'Penghapusan penarikan dana oleh pengguna',
        ]);
    }
}

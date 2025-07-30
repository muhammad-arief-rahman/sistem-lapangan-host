<?php

namespace App\Observers;

use App\Models\Mutation;

class MutationObserver
{
    public function created(Mutation $mutation)
    {
        $mutation->user->updateBalance();

        $type = $mutation->amount > 0 ? 'success' : 'info';
        $action = $mutation->amount > 0 ? 'ditambahkan' : 'dikurangi';

        $mutation->user->notify(new \App\Notifications\Database\AddedMutation($mutation, $action, $type));
        $mutation->user->notify(new \App\Notifications\Mail\AddedMutation($mutation, $action, $type));

    }

    public function updated(Mutation $mutation)
    {
        $mutation->user->updateBalance();
    }

    public function deleted(Mutation $mutation)
    {
        $mutation->user->updateBalance();
    }
}

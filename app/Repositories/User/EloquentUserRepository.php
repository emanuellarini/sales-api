<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\Sale;

class EloquentUserRepository implements UserRepository
{
    public function findById($id)
    {
        return User::find($payload['vendedor']);
    }

    public function storeUserableCommission(User $user, Sale $sale)
    {
        $user->userable->commission = $user->userable->commission + $sale->commission;
        $user->userable->save();

        return $user;
    }
}

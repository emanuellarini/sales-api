<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\Sale;

class EloquentUserRepository implements UserRepository
{
    public function findById($id)
    {
        return User::find($id);
    }

    public function storeUserableCommission(User $user, Sale $sale)
    {
        $user->userable->commission = ($user->userable->commission + $sale->commission) * 100;
        $user->userable->save();

        return $user;
    }
}

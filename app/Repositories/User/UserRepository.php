<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\Sale;

interface UserRepository
{
    public function findById($id);
    public function storeUserableCommission(User $user, Sale $sale);
}

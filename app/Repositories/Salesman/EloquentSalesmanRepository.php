<?php

namespace App\Repositories\Salesman;

use App\Models\Salesman;

class EloquentSalesmanRepository implements SalesmanRepository
{
    public function getAllWithUser()
    {
        return Salesman::with('user')->get();
    }

    public function createWithUser($data)
    {
        $salesman = Salesman::create();
        $salesman->user()->create($data);

        return $salesman;
    }
}

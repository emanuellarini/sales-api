<?php

namespace App\Repositories\Sale;

use App\Models\Sale;

class EloquentSaleRepository implements SaleRepository
{
    public function findByUser($id)
    {
        return Sale::where('user_id', $id)->get();
    }

    public function create($data)
    {
        return Sale::create($data);
    }
}

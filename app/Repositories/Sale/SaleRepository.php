<?php

namespace App\Repositories\Sale;

interface SaleRepository
{
    public function findByUser($id);
    public function create($data);
}

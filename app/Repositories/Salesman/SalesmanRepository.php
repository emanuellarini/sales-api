<?php

namespace App\Repositories\Salesman;

interface SalesmanRepository
{
    public function getAllWithUser();
    public function createWithUser($data);
}

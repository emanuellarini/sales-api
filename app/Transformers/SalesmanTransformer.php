<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Salesman;

class SalesmanTransformer extends TransformerAbstract
{
    public function transform(Salesman $salesman)
    {
        return [
            'id' => $salesman->id,
            'name' => $salesman->user->name,
            'email' => $salesman->user->email,
            'commission' => 'R$ ' . number_format(($salesman->commission / 100), 2, ',', '.'),
        ];
    }
}

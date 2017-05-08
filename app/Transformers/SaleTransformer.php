<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Sale;

class SaleTransformer extends TransformerAbstract
{
    public function transform(Sale $sale)
    {
        return [
            'id' => $sale->id,
            'name' => $sale->user->name,
            'email' => $sale->user->email,
            'amount' => 'R$ ' . number_format($sale->amount , 2, ',', '.'),
            'commission' => 'R$ ' . number_format($sale->commission, 2, ',', '.'),
            'date' => \Carbon\Carbon::parse($sale->created_at)->format('d/m/Y H:i:s'),
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Salesman;

class Sale extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value', 'comission_pct'
    ];

    /**
     * Get the salesman who did the sale.
     */
    public function salesman()
    {
        return $this->belongsTo(Salesman::class);
    }
}

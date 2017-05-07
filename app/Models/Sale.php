<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
     * Get the user who did the sale.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

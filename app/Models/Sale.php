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
        'amount', 'comission_pct', 'user_id'
    ];

    const COMISSION_PCT = 8.5;

    protected $attributes = [
      'commission_pct' => self::COMISSION_PCT
    ];

    /**
     * Get the user who did the sale.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

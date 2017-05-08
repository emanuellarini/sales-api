<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Sale extends Model
{
    const COMISSION_PCT = 0.085;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount', 'comission_pct', 'user_id'
    ];

    /**
     * The default attributes.
     *
     * @var array
     */
    protected $attributes = [
      'commission_pct' => self::COMISSION_PCT
    ];

    /**
     * The appended attributes.
     *
     * @var array
     */
    protected $appends = [
        'commission'
    ];

    /**
     * Get the sale's amount.
     *
     * @param  string  $value
     * @return string
     */
    public function getAmountAttribute($value)
    {
        return $value / 100;
    }

    /**
     * Get the sale's comission.
     *
     * @return string
     */
    public function getCommissionAttribute()
    {
        return round($this->amount * $this->commission_pct);
    }

    /**
     * Get the user who did the sale.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

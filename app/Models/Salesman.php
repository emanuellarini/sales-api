<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Sale;

class Salesman extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'commission'
    ];

    /**
     * Get the salesman user.
     */
    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    /**
     * Get the sales of the salesman.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}

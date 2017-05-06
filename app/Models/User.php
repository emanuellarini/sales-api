<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

      /**
     * Get all of the owning userable models.
     */
    public function userable()
    {
        return $this->morphTo();
    }
}

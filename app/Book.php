<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    // All properties will be mass assignable
    protected $guarded = [];

    public function owner()
    {
        return $this->belongsToMany(User::class);
    }
}

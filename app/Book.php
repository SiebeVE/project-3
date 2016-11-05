<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    // All properties will be mass assignable
    protected $guarded = [];

    // Temp filler for renamed method
    public function owner()
    {
        return $this->owners();
    }

    public function owners() {
        return $this->belongsToMany(User::class)->withPivot(["type", "condition", "status"]);
    }
}

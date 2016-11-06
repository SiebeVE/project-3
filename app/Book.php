<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\ISO639;

class Book extends Model
{
    // All properties will be mass assignable
    protected $guarded = [];

    public function getFullLanguageAttribute() {
        $iso = new ISO639;
        return $iso->languageByCode1($this->attributes['language']);
    }

    public function getImageAttribute($value) {
        return $value ?: '/imgs/nocover1.png';
    }

    // Temp filler for renamed method
    public function owner()
    {
        return $this->owners();
    }

    public function owners() {
        return $this->belongsToMany(User::class)->withPivot(["type", "condition", "status", "price"]);
    }

    public function ownersWithStatus0 () {
        return $this->owners()->wherePivot('status', '0');
    }
}

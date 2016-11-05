<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Matriphe\ISO639\ISO639;

class Book extends Model
{
    // All properties will be mass assignable
    protected $guarded = [];

    public function getFullLanguageAttribute() {
        $iso = new ISO639;
        return $iso->languageByCode1($this->attributes['language']);
    }

    // Temp filler for renamed method
    public function owner()
    {
        return $this->owners();
    }

    public function owners() {
        return $this->belongsToMany(User::class)->withPivot(["type", "condition", "status"]);
    }
}

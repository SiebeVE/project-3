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
        return $this->belongsToMany(User::class)->withPivot(["type", "condition", "status", "price", "id"]);
    }

    public function ownersWithStatus0 () {
        return $this->owners()->wherePivot('status', '0');
    }

    public function getCheapestPrice() {
        $owners = $this->ownersWithStatus0()->get();
        $cheapestPrice = null;
        foreach ($owners as $owner) {
            if(in_array('free', explode(',',$owner->pivot->type))) return 'FREE';

            if(in_array('buy', explode(',',$owner->pivot->type))) {
                if($owner->pivot->price < $cheapestPrice || $cheapestPrice == null) $cheapestPrice = $owner->pivot->price;
            }

        }

        return $cheapestPrice;
    }
}

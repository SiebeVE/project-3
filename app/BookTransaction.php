<?php

namespace App;

use App\User;
use App\Book;
use Illuminate\Database\Eloquent\Model;

class BookTransaction extends Model
{

    // All properties will be mass asignable
    protected $guarded = [];

    public function from()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    public function to()
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    public function book() 
    {
        return $this->belongsTo(BookUser::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookUser extends Model
{
	// Status
	// 0: Book is available to everyone
	// 1: Book is sold but not yet in possession of other party
	// 2: Book is sold to other user
	// 3: Book is borrowed but not yet in possession of other party
	// 4: Book is borrowed and the other party has the book

	protected $guarded = [];

	protected $table = "book_user";

	public function book () {
		return $this->belongsTo(Book::class);
	}

	public function user () {
		return $this->belongsTo(User::class);
	}

	public function setPriceAttribute($value) {
	    $this->attributes['price'] = $value ?: 0;
    }

    public function getPriceAttribute($value) {
        return $value ?: 0;
    }
}

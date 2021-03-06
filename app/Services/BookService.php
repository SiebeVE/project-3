<?php

namespace App\Services;

use Auth;
use App\Book;

class BookService
{
    protected $books;

    public function __construct(Book $books) {
        $this->books = $books;
    }

    public function getDistanceToBooksFromUser($books, $user = null) {
        $user = $user ?: Auth::user();

        if( ! $user) abort(403, 'Not logged in (could not get location from user).');

        $fromLocation = $user->getAddress();
        return $this->getDistanceToBooksFromLocation($books, $fromLocation);
    }

    public function getDistanceToBooksFromLocation($books, $fromLocation) {
        // als we geen boeken krijgen, kunnen we er ook geen terug geven eh
        if(empty($books)) return $books;

        // load owners on the books
        $books = $books->load('owners');

        // Get distance from books
        $availableBooks = [];
        $owner_ids = [];
        $owners = [];

        foreach ($books as $book) {
            $newBook = [
                "id" => $book->id,
                "title"  => $book->title,
                "author" => $book->author,
                "types"   => [],
            ];
            $has_legit_owners = false;
            foreach ($book->owners as $owner) {
                if ($owner->pivot->status == 0) {
                    $has_legit_owners = true;
                    if ( ! in_array($owner->id, $owner_ids)) {
                        // Make a list of unique owners
                        $owner_ids[] = $owner->id;
                        $owners[] = $owner;
                    }

                    // Add empty types property on book
                    $newBook['types'] = [];

                    // loop over available types for this owner
                    $types = explode(',', $owner->pivot->type);
                    foreach ($types as $type) {
                        if ( ! array_key_exists($type, $newBook['types'])) {
                            $newBook['types'][$type] = [];
                        }
                        $newBook['types'][ $type ][] = [
                            "owner_id" => $owner->id,
                            "distance" => 0,
                            "closest"  => false
                        ];
                    }
                }
            }


            //dump($newBook['types']);

            if ($has_legit_owners) {
                $availableBooks[] = $newBook;
            }
        }

        $destinations = "";
        foreach ($owners as $owner) {
            $destinations .= $owner->getAddress() . "|";
        }
        $destinations = str_replace(' ', '+', rtrim($destinations, '|'));
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', env("API_URL_DISTANCE"), [
            'query' => [
                'origins'        => $fromLocation,
                'destinations'   => $destinations,
                'departure_time' => time(),
                'traffic_model'  => 'best_guess',
                'mode'           => 'bicycling',
                'key'            => env("API_KEY_BOOK"),
            ]
        ]);

        $distanceResult = \GuzzleHttp\json_decode($res->getBody());


        // Make array with owner id and distance
        $distanceArray = [];
        foreach ($owner_ids as $key => $owner_id) {
            $distance = $distanceResult->rows[0]->elements[ $key ]->distance->value;

            $distanceArray[ $owner_id ]["distance"] = [
                "value" => $distance,
                "text"  => $distanceResult->rows[0]->elements[ $key ]->distance->text
            ];

            $distanceArray[ $owner_id ]["duration"] = [
                "value" => $distanceResult->rows[0]->elements[ $key ]->duration->value,
                "text"  => $distanceResult->rows[0]->elements[ $key ]->duration->text
            ];
        }

        //Relink the books with the owner and distance
        foreach ($availableBooks as $key_book => $book) {
            debug($book["title"]);
            foreach ($book["types"] as $key_type => $type) {
                debug($key_type);
                $closest = NULL;
                $closest_id = NULL;
                foreach ($type as $key_owner => $owner) {
                    $distanceOwner = $distanceArray[ $owner["owner_id"] ];
                    if ($closest === NULL) {
                        debug("set");
                        $closest = $distanceOwner["duration"]["value"];
                        debug($closest);
                        $closest_id = $owner["owner_id"];
                        debug($closest_id);
                    }
                    if ($distanceOwner["duration"]["value"] <= $closest) {
                        $closest = $distanceOwner["duration"]["value"];
                        $closest_id = $owner["owner_id"];
                    }
                    $availableBooks[ $key_book ]["types"][ $key_type ][ $key_owner ]["distance"] = $distanceOwner;
                }

                debug($closest);
                debug($closest_id);

                foreach ($type as $key_owner => $owner) {
                    if ($owner["owner_id"] == $closest_id) {
                        $availableBooks[ $key_book ]["types"][ $key_type ][ $key_owner ]["closest"] = true;
                    }
                }
            }
        }

        $books->each(function($book, $key) use ($availableBooks) {
            foreach($availableBooks as $availableBook) {
                if($availableBook['id'] === $book->id) {
                    $closestValue = null;
                    $closestTime = "";
                    foreach ($availableBook['types'] as $type => $typedata) {
                        if($closestValue > $typedata[0]['distance']['duration']['value'] || $closestValue == null) {
                            $closestTime = $typedata[0]['distance']['duration']['text'];
                            $closestValue = $typedata[0]['distance']['duration']['value'];
                        }
                    }
                    $book->closestTime = $closestTime;
                    $book->types = $availableBook['types'];
                }
            }
        });

        return $books;
    }
}
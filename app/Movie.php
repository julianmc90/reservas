<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $function_init_date
 * @property string $function_end_date
 * @property Reservation[] $reservations
 */
class Movie extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'function_init_date', 'function_end_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany('App\Reservation', 'movies_id');
    }

    
    /**
     * Get all the movies as a list with a format
     * 
     * @return Array list of movies as assoc list
     */
    public static function getMovieInfoList(){

        /**
         * All movies
         */
        $movies = self::all();
        
        /**
         * Reducing the list and aplly format
         */
        $userInfoList = $movies->reduce(function ($a, $b) {

            $a[$b->id] = $b->name . ', ' .$b->function_init_date. ', ' . $b->function_end_date;
            return $a;
        }, []);

        return $userInfoList;
    }


}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $users_id
 * @property int $movies_id
 * @property int $people
 * @property string $reservation_date
 * @property Movie $movie
 * @property User $user
 * @property PositionsChair[] $positionsChairs
 */
class Reservation extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['users_id', 'movies_id', 'people', 'reservation_date','positions'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function movie()
    {
        return $this->belongsTo('App\Movie', 'movies_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'users_id');
    }

  
}

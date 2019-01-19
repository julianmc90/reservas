<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'identification', 'name', 'last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * Get all the users as a list with a format
     * 
     * @return Array list of users as assoc list
     */
    public static function getUserInfoList(){

        /**
         * All users
         */
        $users = self::all();
        
        /**
         * Reducing the list and aplly format
         */
        $userInfoList = $users->reduce(function ($a, $b) {
            $a[$b->id] = 'id: '.$b->identification . ', ' . $b->name . ' ' .$b->last_name. ', ' . $b->email;
            return $a;
        }, []);

        return $userInfoList;
    }


}

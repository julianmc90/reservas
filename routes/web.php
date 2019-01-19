<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reservation/manageReservation/{id?}', 'ReservationController@manageReservation');
Route::get('/reservation/getSeatingInfo/{reservationDate}/{moviesId}', 'ReservationController@getSeatingInfo');
Route::post('/reservation/reservationsByDate', 'ReservationController@getReservationsByDate');

Route::resource('user', 'UserController');

Route::resource('movie', 'MovieController');

Route::resource('reservation', 'ReservationController');








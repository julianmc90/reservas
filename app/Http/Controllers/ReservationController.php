<?php
/**
 * @author Julian Andres Muñoz Cardozo
 * @email julianmc90@gmail.com
 * @create date 2019-01-19 15:46:58
 * @modify date 2019-01-19 15:50:04
 * @desc Reservations
 */
namespace App\Http\Controllers;
use Validator;
use View;
use Redirect;
use Session;


use App\Reservation;
use App\Movie;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Util\Util;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //paginate reservations with user and movie    
        $reservations = Reservation::with(['user','movie'])->paginate(10);;

        //Decode json string of positions to simple array
        $reservations->getCollection()->transform(function ($r) {
            $r->positions = json_decode($r->positions);
            return $r; 
        });

        // load the view and pass the reservation info
        return View::make('reservation.index')
        ->with('reservations', $reservations);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

     
    }

     /**
      * Manage Reservations
      * Sends the information needed for the creation and editing to the manage view
      * @param Request $request
      * @param Int $id reservation identifier
      * @return \Illuminate\Http\Response
      */
    public function manageReservation(Request $request, $id = null)
    {

        /**
         * Reservation null by default
         */
        $reservation = null;

        /**
         * If is not present the id of the reservation
         */
        if($id != null){
            $reservation = Reservation::find($id);
        }

        /**
         * Get users list info
         */
        $userInfo = User::getUserInfoList();

        /**
         * Get users list info
         */
        $moviesList = Movie::getMovieInfoList();

        /**
         * Getting al the movies
         */
        $movies = Movie::all();

        /**
         * Setting the dates between
         */
        foreach($movies as $movie){
            $movie['datesBetween'] = Util::getDatesBetweenDates($movie->function_init_date, $movie->function_end_date);
        }

        return View::make('reservation.manage')
        ->with('moviesList', $moviesList)
        ->with('movies', $movies)
        ->with('reservation', $reservation)
        ->with('users', $userInfo);        
    }

    /**
     * Get the seats that pretend to be bought and are in use 
     *
     * @param Array $positions array of positions to buy
     * @param String $reservationDate reservation date
     * @param Int $moviesId movie id
     * @param Array $ignorePositions position to ignore because of the editing process
     * @return Array array with the indexes inUseSeats and unavailable with information of positions of seats in use and the complete unavailable positions respectively
     */
    public function getAlreadyInUseSeats($positions,$reservationDate, $moviesId, $ignorePositions = []){

        $alreadyInUseSeats = [];

        $unavailableSeats = $this->getSeatsInfoByMovieAndDate($reservationDate, $moviesId);

        /**
         * If there are positions to ignore
         */
        if(count($ignorePositions)>0){
            //drop the ocurrences
            $unavailableSeats = Util::arrayDiff($ignorePositions, $unavailableSeats);
        }

        //Get the intersection 
        $alreadyInUseSeats = Util::arrayIntersect($positions, $unavailableSeats);

        return ['inUseSeats'=> $alreadyInUseSeats, 'unavailable' => $unavailableSeats];
    
    }

    /**
     * Get all the used positions of seats from a movie in a certain date
     *
     * @param String $reservationDate date of the reservation
     * @param Int $moviesId movie identifier
     * @return void
     */
    public function getSeatsInfoByMovieAndDate($reservationDate, $moviesId){

        /**
         * Query the reservations
         */
        $reservations = Reservation::where(
            [
                'reservation_date'=> $reservationDate, 
                'movies_id'=> $moviesId
            ]
        )->get();
        
        $positions = [];

        /**
         * Covert and merge positions 
         */
        foreach ($reservations as $reservation) {
            $positions = array_merge($positions,json_decode($reservation->positions));

        }

        return $positions;

    }



     /**
      * Get all the used positions from a movie in a certain date
      *
      * @param Request $request Request
      * @param String $reservationDate reservation date
      * @param Int $moviesId
      * @return void
      */
    public function getSeatingInfo(Request $request, $reservationDate, $moviesId){

        $positions = $this->getSeatsInfoByMovieAndDate($reservationDate, $moviesId);
        
        return response()->json($positions);
    }


    /**
     * Validate Reservation by rules
     *
     * @param Request $request
     * @return Array validator errors
     */
    public function validateReservation(Request $request){

        /**
         * Validation rules
         */
        $rules = [
            'users_id' => 'required|numeric',
            'movies_id' => 'required|numeric',
            'people' => 'required|numeric',
            'reservation_date' => 'required|date',
            'positions' => 'required|string'
        ];

        $validator = validator($request->all(), $rules);

        if(!Util::isJson($request->positions)){
            $validator->getMessageBag()->add('positions', "Las posiciones son requeridas");
        }

        $errors = $validator->errors();

        return $errors;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        /**
         * Validating 
         */
        $errors = $this->validateReservation($request);

        /**
         * If is not valid
         */
        if (count($errors) > 0) {

            return response()->json([
                'message' => '¡Errores de validación!',
                'errors'=> $errors], 422);
            
        } else {

            /**
             * Decode positions
             */
            $positions = json_decode($request->positions);    

            /**
             * Get if there are already in use seats
             */
            $inUseSeatsInfo = $this->getAlreadyInUseSeats($positions, $request->reservation_date, $request->movies_id);

            //there are seats in use
            if(count($inUseSeatsInfo['inUseSeats']) > 0){
                return response()->json([
                    'seatsInfo' => $inUseSeatsInfo], 201);
            }else{
            
                /**
                 * Try to create reservation and check if there are errors
                 */
                try{
                    $reservation = new Reservation([
                        'users_id' => $request->users_id,
                        'movies_id' => $request->movies_id,
                        'people' => $request->people,
                        'reservation_date' => $request->reservation_date,
                        'positions' => $request->positions
                    ]);
            
                    $reservation->save();
                    
                    /**
                     * Log when reservation created!
                     */
                    Log::info('Reserva: date: '. $reservation->reservation_date.' user: '. $reservation->user->id . ','. $reservation->user->name .' '. $reservation->user->last_name. ', movie: '.$reservation->movie->id.','.$reservation->movie->name); 
            
                    Session::flash('message', '¡Reserva creada con éxito!');

                    return response()->json([
                        'message' => '¡Reserva creada con éxito!'], 201);
                
                }catch(\Exception $e){
                    // $e->getMessage();
                    
                    return response()->json([
                        'message' => '¡Error creando reserva!, informar al administrador'], 500); 
                }
            }

        }

    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {

        //$reservation = Reservation::find($reservation->id)->with(['user','movie'])->first();
        // show the view and pass the reservation to it
        return View::make('reservation.show')
        ->with('reservation', $reservation);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
     
    }

    /**
     * Update reservation and checks it there are seats in use.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
       
        /**
         * Validating 
         */
        $errors = $this->validateReservation($request);

        /**
         * If is not valid
         */
        if (count($errors) > 0) {

            return response()->json([
                'message' => 'validation errors!',
                'errors'=> $errors], 422);
            
        } else {


            /**
             * Decode new positions
             */
            $positions = json_decode($request->positions);    

            /**
             * Decode positions
             */                    
            $ignorePositions = json_decode($reservation->positions);

            /**
             * Get if there are already in use seats
             */
            $inUseSeatsInfo = $this->getAlreadyInUseSeats($positions, $request->reservation_date, $request->movies_id, $ignorePositions);

            // if there are seats in use
            if(count($inUseSeatsInfo['inUseSeats']) > 0){

                return response()->json([
                    'seatsInfo' => $inUseSeatsInfo], 201);
            }else{

                /**
                 * Try to edit reservation and check if there are errors
                 */
                try{

                    /**
                     * Updating reservation
                     */
                    $reservation->people = $request->people;
                    
                    $reservation->positions = $request->positions;
                    $reservation->reservation_date = $request->reservation_date;
                    $reservation->movies_id = $request->movies_id;

                    $reservation->save();
  
                    Session::flash('message', '¡Reserva editada con éxito!');

                    return response()->json([
                        'message' => '¡Reserva editada con éxito!'], 201);
                    
                }catch(\Exception $e){
                    // $e->getMessage();
                    
                    return response()->json([
                        'message' => '¡Error editando reserva!, informar al administrador'], 500); 
                }

            }

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        /**
         * Try to eliminate reservation and check if there are errors
         */
        try{

            $reservation->delete();
            Session::flash('message', '¡Reserva eliminada con éxito!');
        
        }catch(\Exception $e){
            // $e->getMessage();
            Session::flash('error', '¡Error eliminando reserva!');
        }
        
        // redirect
        return Redirect::to('reservation');
    }



  
}

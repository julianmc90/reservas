<?php
/**
 * @author Julian Andres Muñoz Cardozo
 * @email julianmc90@gmail.com
 * @create date 2019-01-19 15:46:58
 * @modify date 2019-01-19 15:50:04
 * @desc Movies
 */
namespace App\Http\Controllers;

use App\Movie;
use Illuminate\Http\Request;
use View;
use Redirect;
use Session;
class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $movies = Movie::paginate(10);
        // load the view and pass the movies
        return View::make('movie.index')
        ->with('movies', $movies);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return View::make('movie.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required|string',
            'function_init_date' => 'required|date',
            'function_end_date' => 'required|date'
        ];

        $validator = validator($request->all(), $rules);

        /**
         * If there are validation errors
         */    
        if ($validator->fails()) {
            return Redirect::to('movie/create')
                ->withErrors($validator)
                ->withInput();

        } else {

            /**
             * Try to create movie and check if there are errors
             */
            try{
                $movie = new Movie([
                    'name' => $request->name,
                    'function_init_date' => $request->function_init_date,
                    'function_end_date' => $request->function_end_date,
                ]);
                
                $movie->save();
                Session::flash('message', '¡Película creada con éxito!');
            
            }catch(\Exception $e){
                // $e->getMessage();
                Session::flash('error', '¡Error creando película!');
            }

           
            // redirect
            return Redirect::to('movie');

        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function show(Movie $movie)
    {

        // get the user
        $movie = Movie::find($movie->id);
        // show the view and pass the movie to it
        return View::make('movie.show')
        ->with('movie', $movie);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function edit(Movie $movie)
    {

        // get the movie
        $movie = Movie::find($movie->id);
        
        // show the edit form and pass the movie
        return View::make('movie.edit')
            ->with('movie', $movie);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movie $movie)
    {

        $rules = [
            'name' => 'required|string',
            'function_init_date' => 'required|date',
            'function_end_date' => 'required|date'
        ];

        $validator = validator($request->all(), $rules);

        // if there are validation errors
        if ($validator->fails()) {
            return Redirect::to('movie/' . $user->id . '/edit')
                ->withErrors($validator)
                ->withInput();
                // $request->except('password')
        } else {

            /**
             * Try to update movie and check if there are errors
             */
            try{
                $movie->name = $request->name;
                $movie->function_init_date = $request->function_init_date;
                $movie->function_end_date = $request->function_end_date;
        
                $movie->save();
                Session::flash('message', '¡Película actualizada correctamente!');
            
            }catch(\Exception $e){
                // $e->getMessage();
                Session::flash('error', '¡Error actualizando película!');
            }

            // redirect
            return Redirect::to('movie');
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Movie  $movie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        /**
         * Try to update movie and check if there are errors
         */
        try{
            $movie->delete();
            Session::flash('message', '¡Película eliminada correctamente!');
        
        }catch(\Exception $e){
            // $e->getMessage();
            Session::flash('error', '¡Error eliminando película!');
        }

        // redirect
        return Redirect::to('movie');

    }
}

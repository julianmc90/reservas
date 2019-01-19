<?php
/**
 * @author Julian Andres Muñoz Cardozo
 * @email julianmc90@gmail.com
 * @create date 2019-01-19 15:46:58
 * @modify date 2019-01-19 15:50:04
 * @desc Users
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use View;
use Redirect;
use Session;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
        $users = User::paginate(10);
        // load the view and pass the users
        return View::make('user.index')
        ->with('users', $users);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return View::make('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $rules = [
            'identification' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|string',
            'last_name' => 'required|string'
        ];

        $validator = validator($request->all(), $rules);

        /**
         * Validating
         */
        if ($validator->fails()) {
            return Redirect::to('user/create')
                ->withErrors($validator)
                ->withInput();
                // $request->except('password')
        } else {

            /**
             * Try to create user and check if there are errors
             */
            try{
                $user = new User([
                    'identification' => $request->identification,
                    'name' => $request->name,
                    'email' => $request->email,
                    'last_name' => $request->last_name,
                    'password' => bcrypt("1234"),
                    
                ]);

                $user->save();
                Session::flash('message', '¡Usuario creado con exito!');
            }catch(\Exception $e){
                // $e->getMessage();
                Session::flash('error', '¡Error Creando usuario!');
            }

            // redirect
            return Redirect::to('user');

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
        // get the user
        $user = User::find($user->id);


        // show the view and pass the user to it
        return View::make('user.show')
        ->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {

        // get the user
        $user = User::find($user->id);
        
        // show the edit form and pass the user
        return View::make('user.edit')
            ->with('user', $user);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        $rules = [
            'identification' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|string',
            'last_name' => 'required|string'
        ];

        $validator = validator($request->all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('user/' . $user->id . '/edit')
                ->withErrors($validator)
                ->withInput();
                // $request->except('password')
        } else {

            /**
             * Try to create update and check if there are errors
             */
            try{
       
                $user->identification = $request->identification;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->last_name = $request->last_name;
    
                $user->save();
                Session::flash('message', '¡Usuario editado con exito!');
            
            }catch(\Exception $e){
                // $e->getMessage();
                Session::flash('error', '¡Error editando usuario!');
            }
        
            return Redirect::to('user');

        }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        /**
         * Try to eliminate user and check if there are errors
         */
        try{
            $user->delete();
            Session::flash('message', '¡Usuario eliminado con exito!');
        
        }catch(\Exception $e){
            // $e->getMessage();
            Session::flash('error', '¡Error eliminando usuario!');
        }
        return Redirect::to('user');
    }
    
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }


    public function authenticate(Request $request)
   {
       // grab credentials from the request
       $credentials = $request->only('email','password');
      

       try {
           // attempt to verify the credentials and create a token for the user
           if (! $token = JWTAuth::attempt($credentials)) {

               return response()->json(['error' => 'invalid_credentials'], 401);
           }
       } catch (JWTException $e) {
           // something went wrong whilst attempting to encode the token
           return response()->json(['error' => 'could_not_create_token'], 500);
       }
       // all good so return the token
       return response()->json(compact('token'));
   }
}

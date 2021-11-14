<?php


// # Explicacion del LOGIN de laravel y auth: https://www.youtube.com/watch?v=coSV-njT1Gk&t=499s
// .En este archivo esta toda la logica del LOGIN: -> vendor/laravel/ui/auth-backend/AutenticatesUsers.php
// # EMAIL: tutotorial -> https://www.youtube.com/watch?v=e0ynchA_sBA

namespace App\Http\Controllers;

use App\Mail\createtoken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * @Route("Route", name="RouteName")
     */
    public function register(Request $request)
    {
       $validateData = $request->validate([
           'name' => 'required|string|max:255',
           'email' => 'required|string|email|max:255|unique:users',
           'password' => 'required|string|min:6',

       ]);

       $user = User::create([
        'name' => $validateData['name'],
        'email' => $validateData['email'],
        'password' => Hash::make($validateData['password']),
       ]);
       $token = $user->createToken('auth_token')->plainTextToken;
       
       return response()->json([
           'access_token' => $token,
           'token_type' => 'Bearer' 
       ]);
    }

    public function login(Request $request)
    {
        if(!Auth::attempt($request->only('email', 'password'))){

            return response()->json([
                'message' => 'Error, Invalid credentials'

            ], 401);            

        }else{
            return response()->json([
                'message' => 'Hello '. $request->email
            ], 401);
        }
        
    }

    public function info_user(Request $request)
    {
        return $request->user();
        
    }

    public function mail_token()
    {
        
        $mail_user = auth()->user()->email; 

        $mail_token = new createtoken;
        Mail::to($mail_user)->send($mail_token);

        return response()->json([
            'msg' => "Genial!, te enviamos un correo a, ". $mail_user." con el nuevo TOKEN generado",
        ]);
         
    }

}

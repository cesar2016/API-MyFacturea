<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer' 
        ]);
    }

    public function info_user(Request $request)
    {
        return $request->user();
    }

}

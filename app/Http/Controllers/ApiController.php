<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function register(Request $request) 
    {
        $request->validate ([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully'
        ]);
    }


    public function login(Request $request) 
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!empty($user)) {
            if(Hash::check($request->password, $user->password)){
                $token  = $user->createToken("myToken")->plainTextToken;

                return response()->json([
                    'status' => true,
                    'message' => "login successful",
                    'token' => $token
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => "password didn't match"
            ]);
        }
    }


    public function profile() 
    {
        $data = auth()->user(); //Auth::user();

        return response()->json([
            'status' => true,
            'message' => 'profile data',
            'user' => $data
        ]);
    }


    public function logout(Request $request) 
    {

    }

}

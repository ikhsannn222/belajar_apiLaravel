<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash; 

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        if(!Auth()->attempt($request->only('email','password'))) {
            return response()->json([
                'status'=> false,
                'message'=> 'Gagal Login',
            ], 401);
        }
        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'=> true,
            'data'=> $user,
            'acces_tokken'=> $token,
            'message'=> 'Login Succes!!'
        ], 200);
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return response()->json([
            'status'=>true,
            'message'=>'Logout Succes',
        ], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required|string|max:255',
            'email'=> 'required|string|max:255|unique:users',
            'password'=>'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);
        return response()->json([
            'data'=> $user,
            'succes'=> true,
            'message'=> 'User Berhasil Dibuat',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(Request $request) {
        $credentials = $request->only(['email', 'password']);

        if(Auth::attempt($credentials) === false){
            return response()->json('Unauthorized', 401);
        }

        //$user = User::whereEmail($credentials['email'])->first();

        /*if($user === null || Hash::check($credentials['password'], $user->password) === false){
            return response()->json('Unauthorized', 401);
        }*/

        $user = Auth::user();

        $token = $user->createToken('token');

        return response()->json($token->plainTextToken);

    }


}

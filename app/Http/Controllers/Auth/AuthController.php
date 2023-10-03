<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // $engineer = ( Auth::user()->Job_title == 'engineer' ) ? 'engineer' : '' ;
            $token = Auth::user()->createToken('token-name', ['server:update'])->plainTextToken;
            $user = User::find(Auth::user()->id);
            // foreach ($user->stations as $station) {
            // $station;
            // }
            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'error' => 'Error in email or password.'
            ], 401);
        }
    }

    public function logout(Request $request, $id)
    {
        // $request->user()->tokens()->delete();

        $user = User::find($id);
        $user->tokens()->delete();
        return response()->json($request->header('Authorization'));
    }
}
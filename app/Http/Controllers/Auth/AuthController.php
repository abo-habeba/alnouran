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
        // تسجيل الدخول
        if (Auth::attempt($credentials)) {
            // إنشاء رمز مميز
            $user = Auth::User();
            $token = $user->createToken('API Token')->plainTextToken;
            // إرسال رد
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
    public function logout(Request $request)
    {
        //   ->tokens()->where('id', $id)->delete();
        // $user =  $request->user()->currentAccessToken()->delete();
        // $user = $request->user()->tokens();
        $userid = $request->user()->id;
        $user = $request->user()->tokens()->where('id', $userid)->delete();
        // $user->delete();
        return response()->json($user, $userid);
        
    }
}
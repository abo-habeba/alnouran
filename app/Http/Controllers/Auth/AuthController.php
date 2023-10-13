<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
        $tokenId = intval(explode("|", request()->bearerToken())[0]);
        $user = $request->user();
        // $request->session()->invalidate();
        $user->tokens()->where('id', $tokenId)->delete();
        return response()->json([$tokenId, $user]);
        // Auth::logout();
        // $user =  $request->user();
        // $request->session()->regenerateToken();
        // $user->tokens()->delete();
        // $user->tokens()->find($tokenId)->delete();
        // $user->tokens()->where('id', $id)->delete();
        // $user =  $request->user()->currentAccessToken()->delete();
        // $user = $request->user()->tokens();
        // $userid = $request->user()->id;
        // $user = $request->user()->tokens()->where('id', $userid)->delete();
        // $user->delete();
    }
}
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
            $user = $request->user();
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
    public function logout(Request $request, $id)
    {
        $user = User::find($id);
        // $user->tokens()->delete();
        // return response()->json($request->header('Authorization'));
        $user()->tokens()->delete();
        Auth::guard('web')->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.',
        ], 200);
    }
}
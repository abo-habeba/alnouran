<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use App\Models\Station;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('tasks')->get();
        foreach ($users as $user) {
            $user->tasks_count;
        }
        return response()->json([
            'usres' => $users
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required'],
                'email' => ['required', 'email', 'unique:users'],
                'phone' => ['required'],
                'Job_title' => ['required'],
                'password' => ['required', 'min:8'],
                'station_id' => ['required']
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 401);
        };
        $station = Station::find(request()->station_id);
        if (!$station) {
            return response()->json([
                'message' => 'There is no ID matching this station',
            ], 401);
        }
        $user = User::create(request(['name', 'email', 'Job_title', 'phone']) + ['password' => bcrypt(request()->password)]);
        //add many to many relation
        $user->stations()->attach(request()->station_id);
        return Response()->json([
            'user' => $user
        ]);
    }
    public function update($id)
    {
        $user = User::findOrFail($id);

        $user->update(request()->only('name', 'email', 'phone'));

        if (request()->password) {
            $user->update(['password' => bcrypt(request()->password)]);
        }

        return $user->fresh();
    }
    public function show($userId)
    {
        $user = User::findOrFail($userId);
        return $user->load('stations.tasks.user', 'stations.reports.user', 'stations.reports.comments');
    }
    public function getUserReports($userId)
    {
        $user = User::find($userId);
        $stationIds = $user->stations->pluck('id');
        $reports = Report::whereIn('station_id', $stationIds)->with('user', 'station')->orderBy('created_at', 'DESC')->get();
        return response()->json($reports);
    }
    public function authCheck()
    {
        if (request()->tokens) {
            // foreach (Auth::user()->tokens as $token) {
            //     $token;
            // }
            return response()->json(Auth::user()->tokens);
        }
        return response()->json(Auth::check());
    }
}
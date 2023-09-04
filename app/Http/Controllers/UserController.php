<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Station;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
    public function index(){
            $users = User::withCount('tasks')->get();
            foreach ($users as $user) {
                $user->tasks_count;
            }
            return response()->json([
                'usres' => $users
            ]);
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),
        [
            'name' => ['required'],
            'email' => ['required','email', 'unique:users'],
            'phone' => ['required'],
            'Job_title' => ['required'],
            'password' => ['required', 'min:8'],
            'station_id' => ['required']
        ]);
        if ($validator->fails()) {
        return response()->json([
                'message' => $validator->errors(),
            ],401);
        };
        $station = Station::find(request()->station_id);
        if (!$station) {
            return response()->json([
                'message' => 'There is no ID matching this station',
            ],401);
        }
        $user= User::create(request(['name','email','Job_title','phone'])+['password'=>bcrypt(request()->password)]);
        //add many to many relation
        $user->stations()->attach(request()->station_id);
        return Response()->json([
            'user' => $user
        ]);
    
    }
    public function show($userId){
    $user = User::findOrFail($userId);
    return $user->load('stations.tasks.user','stations.posts.user');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StationController extends Controller
{
    public function index()
    {
        $stations = Station::with('tasks')->get();
        return Response()->json([
         'stations' => $stations
     ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'name' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ],401);
        } else {
        $Station= Station::create(request()->all());
        return Response()->json([
            'Stations' => $Station
        ]);
        }
    }
    public function show(string $id)
    {
        $Station = Station::find($id);
         $Station->tasks;
    
        if ($Station) {
            return response()->json([
                'Station' => $Station,
            ]);
        } else {
        return Response()->json([
            'message' => 'no Station'
        ]);
        }
    }
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(),
        [
            'name' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ]);
        }
        $Station = Station::find($id);
        if (!$Station) {
            return response()->json([
                'message' => 'The station does not exist',
            ]);
        }
        $Station->update(request()->all());
        if ($Station) {
        return Response()->json([
            'Stations' => $Station
        ]);
        }
        return response()->json([
            'message' => 'no Stations update',
        ]);
    }
    public function destroy(string $id)
    {
        $Station = Station::find($id);
        if (!$Station) {
            return response()->json([
                'message' => 'The station does not exist',
            ]);
        }
        $Station->delete();
        return Response()->json([
            'message' => 'The station has been deleted'
        ]);

    }
}
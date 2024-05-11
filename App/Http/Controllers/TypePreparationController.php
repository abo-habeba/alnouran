<?php

namespace App\Http\Controllers;

use App\Models\Preparation;
use App\Models\User;
use App\Models\TypePreparation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class TypePreparationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $user = User::find($userId);
        /** @var User */
        $user = Auth::User();
        $stations = $user->stations;
        if ($stations->isEmpty()) {
            return ['لا يوجد محطات مرتبطة بالمستخدم'];
        }
        $stationIds = $stations->pluck('id');
        $typePreparations = TypePreparation::whereIn('station_id', $stationIds)->get();
        $typePreparations->load('latestPreparationActual');
        return $typePreparations;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|unique:type_preparations|max:255',
            'description' => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $typePreparation = TypePreparation::create($request->all());

        return response()->json($typePreparation, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $typePreparation = TypePreparation::find($id);

        if (!$typePreparation) {
            return response()->json(['message' => 'Type preparation not found'], 404);
        }

        return response()->json($typePreparation, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $typePreparation = TypePreparation::find($id);

        if (!$typePreparation) {
            return response()->json(['message' => 'Type preparation not found'], 404);
        }

        $rules = [
            'name' => 'required|string|unique:type_preparations,name,' . $id . '|max:255',
            'description' => 'required|string',
            'user_id' => 'required|unsignedBigInteger|exists:users,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $typePreparation->update($request->all());

        return response()->json($typePreparation, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $typePreparation = TypePreparation::find($id);

        if (!$typePreparation) {
            return response()->json(['message' => 'Type preparation not found'], 404);
        }

        $typePreparation->delete();

        return response()->json(['message' => 'Type preparation deleted successfully'], 200);
    }
}

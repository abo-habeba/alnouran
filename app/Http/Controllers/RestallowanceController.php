<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restallowance;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RestallowanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::id();

        // $currentMonth = date('m');  // الحصول على الشهر الحالي
        $currentYear = date('Y');  // الحصول على السنة الحالي

        $restAllowance = Restallowance::query()
            ->where('user_id', $user_id)
            ->whereYear('date', $currentYear)
            ->get();
        if ($restAllowance && $restAllowance->isNotEmpty()) {
            # code...
            return $restAllowance;
        } else {
            return false;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::transaction(function ($request) {
            $description = $request->input('description');
            $user_id = Auth::id();
            $dated = $request->input('date');
            /** @var User */
            $user = Auth::User();
            $restallowance = Restallowance::create([
                'description' => $description,
                'user_id' => $user_id,
                'date' => $dated,
            ]);
            $restBalance = $user->restBalance;
            $user->restBalance()->update([
                'balance' => $restBalance->balance + 1,
            ]);
            return $restallowance;
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Restallowance $restallowance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restallowance $restallowance)
    {
        $restallowance->state = !$restallowance->state;
        $restallowance->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restallowance $restallowance)
    {
        //
    }
}

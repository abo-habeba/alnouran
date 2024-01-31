<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Absence;
use Illuminate\Http\Request;
use App\Models\Restallowance;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->countYear) {
            // الحصول على تاريخ بداية الشهر الحالي
            $startOfMonth = Carbon::now()->startOfMonth();

            // استعلام Eloquent للحصول على عدد السجلات من بداية الشهر
            $count = auth()->user()->absences->where('created_at', '>=', $startOfMonth)->count();

            return response()->json(['count' => $count]);
        }
        if (request()->countMonth) {
            // الحصول على تاريخ بداية السنة الحالية
            $startOfYear = Carbon::now()->startOfYear();

            // استعلام Eloquent للحصول على عدد السجلات من بداية السنة
            $count = auth()->user()->absences->where('created_at', '>=', $startOfYear)->count();

            return response()->json(['count' => $count]);
        }
        if (request()->countPerMonth) {
            // الحصول على تاريخ بداية السنة الحالية
            $startOfYear = Carbon::now()->startOfYear();

            // الحصول على تاريخ نهاية السنة الحالية
            $endOfYear = Carbon::now()->endOfYear();

            // استعلام Eloquent للحصول على عدد السجلات لكل شهر خلال السنة
            $countsPerMonth = auth()->user()->absences->whereBetween('created_at', [$startOfYear, $endOfYear])
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->groupByRaw('MONTH(created_at)')
                ->get();

            return response()->json(['countsPerMonth' => $countsPerMonth]);
        }
        $user_id = Auth::id();

        // $currentMonth = date('m');  // الحصول على الشهر الحالي
        $currentYear = date('Y');  // الحصول على السنة الحالي

        $absences = Absence::query()
            ->where('user_id', $user_id)
            ->whereYear('date', $currentYear)
            ->get();
        return $absences;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Type = request()->Type;
        $startDate = Carbon::parse(request()->start_date);
        $endDate = Carbon::parse(request()->end_date);
        $dates = $startDate->daysUntil($endDate)->toArray();


        DB::transaction(function () {

            $description = request()->description;
            $Type = request()->Type;
            $rest_id = request()->rest_id;
            $user_id = Auth::id();
            $startDate = Carbon::parse(request()->start_date);
            $endDate = Carbon::parse(request()->end_date);
            $dates = $startDate->daysUntil($endDate)->toArray();
            $absences = [];
            /** Types of vacations
             *
             * اعتيادية	Regular
             * بدل راحة	Rest allowance
             * عارضة	Casual
             * اجازة عمرة	Umrah leave
             * تجنيد	Recruitment
             * اجازة وضع	Maternity leave
             * اجازة تعويضية	Compensatory leave
             * اخري	Other
             */
            // Add regular Balance
            /** @var User */
            $user = Auth::User();
            // $countLeave = count($absences);

            if ($Type == 'Rest allowance') {
                //=============
                $restallowance = Restallowance::findOrFail($rest_id);
                if ($restallowance) {

                    foreach ($dates as $date) {
                        $absence = Absence::create([
                            'description' => $description,
                            'Type' => $Type,
                            'user_id' => $user_id,
                            'date' => $date,
                        ]);
                        array_push($absences, $absence);
                    }

                    $restallowance->update([
                        'state' => false,
                    ]);
                    $restBalance = $user->restBalance;
                    $user->restBalance()->update([
                        'balance' => $restBalance->balance - 1,
                    ]);
                    return response()->json([
                        'data' => $restallowance
                    ]);
                } else {
                    return response()->json([
                        'message' => 'لم يتم العثور على أي سجل يطابق الشروط المحددة'
                    ], 404);
                }
                //=============
            } else {

                $exists = Absence::whereIn('date', $dates)->pluck('date');
                if ($exists->isEmpty()) {
                    foreach ($dates as $date) {
                        $absence = Absence::create([
                            'description' => $description,
                            'Type' => $Type,
                            'user_id' => $user_id,
                            'date' => $date,
                        ]);
                        array_push($absences, $absence);
                    }

                    // ($Type == 'Regular' ||  $Type == 'Casual')
                    $regularBalance = $user->regularBalance;
                    $user->regularBalance()->update([
                        'balance' => $regularBalance->balance - count($absences),
                    ]);
                } else {
                    return response()->json($exists, 409);
                }
            }

            return $absences;
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Absence $absence)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absence $absence)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absence $absence)
    {
        $deleted = DB::transaction(function () use ($absence) {
            $user = auth()->user();
            $absence->delete();
            $regularBalance = $user->regularBalance;
            $regularBalance->balance += 1;
            $regularBalance->save();
        });
        return $deleted;
    }
}

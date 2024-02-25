<?php

namespace app\Traits;

use Carbon\Carbon;
use App\Models\Absence;
use App\Models\Restallowance;
use Illuminate\Support\Facades\Auth;

trait HelperTrait
{

    public function resetAllownesState($restallowance, $state)
    {
        $restallowance->update([
            'state' => $state,
        ]);
        return $restallowance->state;
    }

    public function updateBalance($classBalance, $operation, $balance)
    {
        $classBalance()->update([
            'balance' => $classBalance->balance . $operation . $balance,
        ]);
    }

    public function saveAbsences($request)
    {
        $absences = [];
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $dates = $startDate->daysUntil($endDate)->toArray();
        foreach ($dates as $date) {
            $data = [
                'description' => $request->description,
                'Type' => $request->Type,
                'user_id' => Auth::user()->id,
                'date' => $date,
                'rest_id' => $request->rest_id,
            ];
            array_push($absences, Absence::saveItem($data));
        }
        return $absences;
    }

    public function addAbsenceTypeRest($dailyFife, $rest_id)
    {
        /** @var User */
        $user = Auth::User();
        $restallowance = Restallowance::findOrFail($rest_id);
        if ($restallowance) {
            $absences = $this->saveAbsences(request());
            if ($dailyFife == true) {
                if ($restallowance->state == 5) {
                    $this->resetAllownesState($restallowance, 0);
                } else {
                    $this->resetAllownesState($restallowance, 5);
                }
                // $restBalance = Auth::user()->restBalance;
                $this->updateBalance($user->restBalance, '-', 0.5);
            } else {
                $this->updateBalance($user->restBalance, '-', 1);
            }
            return $absences;
        }
    }
    public function addAbsenceTypeRegular($dailyFife)
    {
        /** @var User */
        $user = Auth::User();
        $absences = $this->saveAbsences(request());
        if ($dailyFife == true) {
            $this->updateBalance($user->regularBalance, '-', 0.5);
        } else {
            $this->updateBalance($user->regularBalance, '-', count($absences));
        }
    }
}
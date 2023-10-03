<?php

namespace App\Http\Controllers;

use App\Models\TimersRecord;

class TimerController extends Controller
{
    public function getTimerData()
    {
        return ['timers' => TimersRecord::all()];
    }

    public function getTimerDataRegions()
    {
        $data = [];
        $pull = TimersRecord::all();
        $pull = $pull->unique('region_id');
        $pull = $pull->sortBy('region');

        foreach ($pull as $pull) {
            $data1 = [];
            $data1 = [
                'text' => $pull['region'],
                'value' => $pull['region_id'],
            ];

            array_push($data, $data1);
        }

        return ['timersregions' => $data];
    }

    public function updateTimerData()
    {
        $status = checkeve();
        if ($status == 1) {
            timeUpdate();
        }
    }
}

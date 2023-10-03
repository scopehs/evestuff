<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\NewSystemNode;
use App\Models\NewUserNode;
use App\Models\System;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $systemlist = [];
        $systems = System::all();
        foreach ($systems as $system) {
            $data = [];
            $data = [
                'text' => $system->system_name,
                'value' => $system->id,
            ];

            array_push($systemlist, $data);
        }

        return ['systemlist' => $systemlist];
    }

    public function checkedAt(Request $request, $systemID)
    {
        $s = System::where('id', $systemID)->first();
        $s->update(['checked_id' => $request->user_id, 'scouted_at' => now()]);
        broadcastsystemSolo($systemID, 7);
        operationInfoSoloSystemBCast($systemID, 16);
    }

    public function editTidi(Request $request, $systemID)
    {
        $s = System::where('id', $systemID)->first();
        $s->update(['tidi' => $request->tidi]);

        $systemNodes = NewSystemNode::where('system_id', $systemID)
            ->whereIn('node_status', [7, 8, 9])->whereNotNull('base_time')
            ->get();

        foreach ($systemNodes as $systemNode) {
            $time_passed = strtotime(now()) - strtotime($systemNode->input_time);
            $base_time = $systemNode->base_time - $time_passed;
            $time_left = $base_time / ($request->tidi / 100);
            if ($time_left <= 0) {
                $time_left = $time_left * -1;
            }
            $end_time = now()->modify('+ ' . round($time_left) . ' seconds');

            $systemNode->end_time = $end_time;
            $systemNode->input_time = now();
            $systemNode->base_time = $base_time;
            $systemNode->save();
        }

        $systemNodes = NewSystemNode::where('system_id', $systemID)->get();
        foreach ($systemNodes as $systemNode) {
            $userNodes = NewUserNode::where('node_id', $systemNode->id)
                ->where('node_status_id', 3)->whereNotNull('base_time')->get();

            foreach ($userNodes as $userNode) {
                $time_passed = strtotime(now()) - strtotime($userNode->input_time);
                $base_time = $userNode->base_time - $time_passed;
                $time_left = $base_time / ($request->tidi / 100);
                if ($time_left <= 0) {
                    $time_left = $time_left * -1;
                }
                $end_time = now()->modify('+ ' . round($time_left) . ' seconds');

                $userNode->end_time = $end_time;
                $userNode->input_time = now();
                $userNode->base_time = $base_time;
                $userNode->save();
            }
        }

        broadcastsystemSolo($systemID, 7);
        operationInfoSoloSystemBCast($systemID, 16);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function systemsinconstellation($id)
    {
        $constid = Campaign::where('link', $id)->value('constellation_id');

        return ['systems' => System::where('constellation_id', $constid)->select('id', 'system_name')->get()];
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Events\ChillSheetUpdate;
use App\Events\RcSheetUpdate;
use App\Events\WelpSheetUpdate;
use App\Models\ChillStationRecords;
use App\Models\Station;
use App\Models\User;
use App\Models\WelpStationRecords;
use Illuminate\Http\Request;

class RcGsolUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function addGsoltoStation(Request $request, $id)
    {
        $gsolNameID = Station::where('id', $id)->value('rc_gsol_id');
        $gsolName = User::where('id', $gsolNameID)->value('name');
        $s = Station::where('id', $id)->get();
        foreach ($s as $s) {
            $s->rc_gsol_id = $request->user_id;
            $s->save();
        }
        $message = StationRecordsSolo(4, $id);
        if ($message) {
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new RcSheetUpdate($flag));
        }

        // $message = ChillStationRecords::where('id', $id)->first();
        // if ($message) {
        //     $flag = collect([
        //         'message' => $message,
        //     ]);
        //     broadcast(new ChillSheetUpdate($flag));
        // }

        // $message = WelpStationRecords::where('id', $id)->first();
        // if ($message) {
        //     $flag = collect([
        //         'message' => $message,
        //     ]);
        //     broadcast(new WelpSheetUpdate($flag));
        // }
    }

    public function removeGsoltoStation($id)
    {
        $gsolNameID = Station::where('id', $id)->value('rc_gsol_id');
        $gsolName = User::where('id', $gsolNameID)->value('name');
        $s = Station::where('id', $id)->get();
        foreach ($s as $s) {
            $s->rc_gsol_id = null;
            $s->save();
        }
        $message = StationRecordsSolo(4, $id);
        if ($message) {
            $flag = collect([
                'message' => $message,
            ]);
            broadcast(new RcSheetUpdate($flag));
        }

        // $message = ChillStationRecords::where('id', $id)->first();
        // if ($message) {
        //     $flag = collect([
        //         'message' => $message,
        //     ]);
        //     broadcast(new ChillSheetUpdate($flag));
        // }

        // $message = WelpStationRecords::where('id', $id)->first();
        // if ($message) {
        //     $flag = collect([
        //         'message' => $message,
        //     ]);
        //     broadcast(new WelpSheetUpdate($flag));
        // }
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

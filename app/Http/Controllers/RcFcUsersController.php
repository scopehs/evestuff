<?php

namespace App\Http\Controllers;

use App\Events\ChillSheetUpdate;
use App\Events\RcSheetUpdate;
use App\Events\WelpSheetUpdate;
use App\Models\ChillStationRecords;
use App\Models\RcFcUsers;
use App\Models\Station;
use App\Models\User;
use App\Models\WelpStationRecords;
use Illuminate\Http\Request;

class RcFcUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fclist = [];
        $fcs = RcFcUsers::all();
        foreach ($fcs as $fc) {
            $data = [];

            $name = User::where('id', $fc->user_id)->value('name');

            $data = [
                'id' => $fc->id,
                'user_id' => $fc->user_id,
                'fleet' => $fc->fleet,
                'name' => $name,
            ];

            array_push($fclist, $data);
        }

        return ['fcs' => $fclist];
    }

    public function newfc(Request $request)
    {
        $check = User::where('name', $request->char_name)->count();
        // dd($check);
        if ($check == null) {
            $id = User::where('id', '>', 9999999999)->max('id');
            if ($id == null) {
                $id = 10000000000;
            } else {
                $id = $id + 1;
            }

            $new = User::Create(['name' => $request->char_name]);
            $new->update(['id' => $id]);
        } else {
            $id = User::where('name', $request->char_name)->value('id');
        }
        RcFcUsers::Create(['user_id' => $id]);
        $flag = collect([
            'flag' => 3,
        ]);
        broadcast(new RcSheetUpdate($flag));
        broadcast(new ChillSheetUpdate($flag));
        broadcast(new WelpSheetUpdate($flag));
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

    public function addFCtoStation(Request $request, $id)
    {
        $check = RcFcUsers::where('user_id', $request->user_id)->count();

        if ($check == 0) {
            RcFcUsers::Create(['user_id' => $request->user_id])->get();
        } else {
            RcFcUsers::where('user_id', $request->user_id)->get();
        }

        $fcid = RcFcUsers::where('user_id', $request->user_id)->value('id');
        $userid = RcFcUsers::where('user_id', $request->user_id)->value('user_id');
        $s = Station::where('id', $id)->get();
        foreach ($s as $s) {
            $s->rc_fc_id = $fcid;
            $s->save();
        }

        $fcname = User::where('id', $userid)->value('name');

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

    public function addFCadd(Request $request, $id)
    {
        $s = Station::where('id', $id)->get();
        foreach ($s as $s) {
            $s->update($request->all());
        }
        $userid = RcFcUsers::where('id', $request->rc_fc_id)->value('user_id');
        $userName = User::where('id', $userid)->value('name');

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

    public function removeFCtoStation($id)
    {
        $fcid = Station::where('id', $id)->value('rc_fc_id');
        $userid = RcFcUsers::where('id', $fcid)->value('user_id');
        $userName = User::where('id', $userid)->value('name');
        $s = Station::where('id', $id)->get();
        foreach ($s as $s) {
            $s->rc_fc_id = null;
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

    public function removeFC($id)
    {
        $user_id = RcFcUsers::where('id', $id)->value('user_id');
        // dd($fc);
        if ($user_id > 9999999999) {
            $u = User::where('id', $user_id)->get();
            foreach ($u as $u) {
                $u->delete();
            }
        }
        $s = Station::where('rc_fc_id', $id)->get();
        foreach ($s as $s) {
            $s->rc_fc_id = 0;
        }
        $r = RcFcUsers::where('id', $id)->get();
        foreach ($r as $r) {
            $r->delete();
        }
        $flag = collect([
            'flag' => 2,
        ]);
        broadcast(new RcSheetUpdate($flag));
        broadcast(new ChillSheetUpdate($flag));
        broadcast(new WelpSheetUpdate($flag));
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

<?php

namespace App\Http\Controllers;

use App\Events\AmmoRequestDelete;
use App\Events\AmmoRequestNew;
use App\Events\AmmoRequestUpdate;
use App\Events\StationNotificationUpdate;
use App\Models\AmmoRequest;
use App\Models\AmmoRequestRecords;
use App\Models\Station;
use App\Models\StationItemJoin;
use App\Models\StationItems;
use Illuminate\Http\Request;

class AmmoRequestController extends Controller
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

    public function loadAmmoRequestData()
    {
        $coreData = [];
        $items = [];
        $stations = Station::where('ammo_request_id', '!=', null)->get();
        foreach ($stations as $station) {
            if ($station->r_cored == 'Yes') {
                $core = 'Yes';
            } else {
                $core = 'No';
            }

            $data1 = [
                'station_id' => $station->id,
                'cored' => $core,
            ];

            array_push($coreData, $data1);

            $joins = StationItemJoin::where('station_id', $station->id)->get();
            foreach ($joins as $join) {
                $name = StationItems::where('id', $join->station_item_id)->first();
                $data = [
                    'station_id' => $join->station_id,
                    'item_name' => $name->item_name,
                    'item_id' => $name->id,
                ];
                array_push($items, $data);
            }
        }

        return [
            'cores' => $coreData,
            'fit' => Station::where('r_hash', '!=', null)->where('ammo_request_id', '!=', null)->get(),
            'items' => $items,
            'ammorequest' => AmmoRequestRecords::all(),
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new = new AmmoRequest();
        $new->station_id = $request->station_id;
        $new->current_ammo = $request->current_ammo;
        $new->request_text = $request->request_text;
        $new->save();
        $s = Station::where('id', $new->station_id)->first();
        $s->ammo_request_id = $new->id;
        $s->save();
        $message = stationRecordSolo($new->station_id);
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new StationNotificationUpdate($flag));

        $message = AmmoRequestRecords::where('id', $new->id)->first();
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new AmmoRequestNew($flag));
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
        $a = AmmoRequest::find($id)->get();
        foreach ($a as $a) {
            $a->user_id = $request->user_id;
            $a->save();
        }

        $message = AmmoRequestRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
        ]);
        broadcast(new AmmoRequestUpdate($flag));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $flag = collect([
            'id' => $id,
        ]);
        broadcast(new AmmoRequestDelete($flag));
        $a = AmmoRequest::where('id', $id)->delete();
        foreach ($a as $a) {
            $a->delete();
        }
        $s = Station::where('ammo_request_id', $id)->first()->get();
        foreach ($s as $s) {
            $s->update(['ammo_request_id' => null]);
        }
    }
}

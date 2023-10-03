<?php

namespace App\Http\Controllers;

use App\Events\ChillSheetUpdate;
use App\Events\RcSheetUpdate;
use App\Events\StationNotificationUpdate;
use App\Events\StationUpdateCoord;
use App\Models\ChillStationRecords;
use App\Models\Station;
use App\Models\StationRecords;
use App\Models\StationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChillStationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];

        return ['stations' => $data];
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

    public function chillSheetListRegion()
    {
        $data = [];
        // $pull = ChillStationRecords::where('show_on_chill', 1)->get();
        // $pull = $pull->unique('region_id');
        // $pull = $pull->sortBy('region_name');
        // foreach ($pull as $pull) {
        //     $data1 = [];
        //     $data1 = [
        //         'text' => $pull['region_name'],
        //         'value' => $pull['region_id'],
        //     ];

        //     array_push($data, $data1);
        // }

        // // dd($data);

        return ['chillsheetlistRegion' => $data];
    }

    public function test()
    {
        // dd(ChillStationRecords::where('show_on_chill', 1)->get());
        $data = [];
        // $pull = ChillStationRecords::where('show_on_chill', 1)->get();
        // $pull = $pull->unique('status_id');
        // $pull = $pull->sortBy('status_name');
        // foreach ($pull as $pull) {
        //     $text = str_replace('Upcoming - ', '', $pull['status_name']);
        //     $data1 = [];
        //     $data1 = [
        //         'text' => $text,
        //         'value' => $pull['status_id'],
        //     ];

        //     array_push($data, $data1);
        // }

        // dd($data);

        return ['chillsheetlistStatus' => $data];
    }

    public function chillSheetListStatus()
    {
        $data = [];
        // $pull = ChillStationRecords::where('show_on_chill', 1)->get();
        // $pull = $pull->unique('status_id');
        // $pull = $pull->sortBy('status_name');
        // foreach ($pull as $pull) {
        //     $text = str_replace('Upcoming - ', '', $pull['status_name']);
        //     $data1 = [];
        //     $data1 = [
        //         'text' => $text,
        //         'value' => $pull['status_id'],
        //     ];

        //     array_push($data, $data1);
        // }

        // dd($data);

        return ['chillsheetlistStatus' => $data];
    }

    public function stationdone($id)
    {
        // $s = Station::where('id', $id)->first();
        // $s->update([
        //     'show_on_chill' => 2,
        //     'station_status_id' => 10,
        //     'rc_fc_id' => null,
        //     'rc_gsol_id' => null,
        //     'rc_recon_id' => null,
        //     'rc_id' => null,
        //     'timer_image_link' => null,
        // ]);
        // $message = ChillStationRecords::where('id', $id)->first();
        // $flag = collect([
        //     'message' => $message,
        // ]);
        // broadcast(new ChillSheetUpdate($flag));
    }

    public function chillSheetListType()
    {
        $data = [];
        // $pull = ChillStationRecords::where('show_on_chill', 1)->get();
        // $pull = $pull->unique('item_id');
        // $pull = $pull->sortBy('item_name');
        // foreach ($pull as $pull) {
        //     $data1 = [];
        //     $data1 = [
        //         'text' => $pull['item_name'],
        //         'value' => $pull['item_id'],
        //     ];

        //     array_push($data, $data1);
        // }

        // dd($data);

        return ['chillsheetlistType' => $data];
    }

    public function chillEditUpdate(Request $request, $id)
    {
        // dd($id);
        $now = now();

        $oldStation = Station::where('id', $id)->first();
        $oldStatus = StationStatus::where('id', $oldStation->station_status_id)->value('name');
        $oldStatus = str_replace('Upcoming - ', '', $oldStatus);

        $s = Station::find($id)->get();
        foreach ($s as $s) {
            $s->update($request->all());
        }
        $newStation = Station::where('id', $id)->first();
        $newStatus = StationStatus::where('id', $newStation->station_status_id)->value('name');
        $newStatus = str_replace('Upcoming - ', '', $newStatus);

        // $message = StationRecords::where('id', $id)->first();
        // $flag = collect([
        //     'message' => $message,
        // ]);
        // broadcast(new StationNotificationUpdate($flag));
        // broadcast(new StationUpdateCoord($flag));
        // broadcast(new ChillSheetUpdate($flag));

        // $message = ChillStationRecords::where('id', $id)->first();
        // if ($message) {
        //     $flag = collect([
        //         'message' => $message,
        //     ]);
        //     broadcast(new ChillSheetUpdate($flag));
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
        $oldStatusID = Station::where('id', $id)->value('station_status_id');
        $oldStatusName = StationStatus::where('id', $oldStatusID)->value('name');
        $oldStatusName = str_replace('Upcoming - ', '', $oldStatusName);

        // $RCmessage = ChillStationRecords::where('id', $id)->first();
        // if ($RCmessage) {
        //     $RCmessageSend = [
        //         'id' => $RCmessage->id,
        //         'show_on_chill' => 0,
        //     ];
        //     $flag = collect([
        //         'message' => $RCmessageSend,
        //     ]);
        //     broadcast(new RcSheetUpdate($flag));
        //     broadcast(new ChillSheetUpdate($flag));
        // }

        $newStatusID = $request->station_status_id;
        $newStatusName = StationStatus::where('id', $newStatusID)->value('name');
        $newStatusName = str_replace('Upcoming - ', '', $newStatusName);
        $new = Station::find($id)->update($request->all());
        $now = now();
        $s = Station::find($id)->first();
        $s->update([
            'added_by_user_id' => Auth::id(),
            'rc_id' => null,
            'rc_fc_id' => null,
            'rc_gsol_id' => null,
            'rc_recon_id' => null,
        ]);
        // $message = StationRecords::where('id', $id)->first();
        // $flag = collect([
        //     'message' => $message,
        // ]);
        // broadcast(new StationNotificationUpdate($flag));
        // broadcast(new StationUpdateCoord($flag));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $s = Station::where('id', $id)->get();
        foreach ($s as $s) {
            $s->update(['show_on_chill' => 0]);
        }
        $flag = collect([
            'flag' => 4,
        ]);
        broadcast(new ChillSheetUpdate($flag));
    }
}

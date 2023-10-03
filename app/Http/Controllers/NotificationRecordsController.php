<?php

namespace App\Http\Controllers;

use App\Events\NotificationChanged;
use App\Models\NotificationRecords;
use App\Models\Region;
use Illuminate\Http\Request;
use utils\Notificationhelper\Notifications;

class NotificationRecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        ////========API FOR NOTIFICATIONS=========
        // return ['notifications' => NotificationRecords::all()];

        $now = now()->subHours(3);

        return ['notifications' => NotificationRecords::where('timestamp', '>=', $now)->get()];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /////
    }

    public function regionLink($region_id)
    {
        $now = now()->subHours(3);
        $http = 'https://evemaps.dotlan.net/map/';
        $region = Region::where('id', $region_id)->get();
        foreach ($region as $region) {
            if ($region->region_name == 'Period Basis') {
                $http = $http . 'Period_Basis/';
            } else {
                $http = $http . $region->region_name . '/';
            }
        }
        $link = NotificationRecords::where('region_id', $region_id)->where('timestamp', '>=', $now)->where('status_id', '<', 10)->get()->pluck('system_name');
        $count = $link->count();
        // dd($count);
        if ($count == 0) {
            return ['link' => 'nope'];
        }
        $link = $link->unique();
        // dd($link);
        foreach ($link as $link) {
            $http = $http . $link . ',';
        }
        $http = substr($http, 0, -1);
        $http = $http . '#adm';

        return ['link' => $http];
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

        // dd($user);
        // dd($request,$id);
        $n = NotificationRecords::find($id)->get();
        foreach ($n as $n) {
            $n->update($request->all());
        }
        $notifications = NotificationRecords::find($id);
        if ($notifications->status_id != 10) {
            broadcast(new NotificationChanged($notifications))->toOthers();
        }
        // broadcast(new NotificationChanged($notifications));
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

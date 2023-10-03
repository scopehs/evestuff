<?php

namespace App\Http\Controllers;

use App\Events\FleetKeysUpdate;
use App\Models\KeyFleetJoin;
use App\Models\KeyFleetJoinController;
use Illuminate\Http\Request;

class KeyFleetJoinControllerController extends Controller
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
        KeyFleetJoin::create($request->all());
        $flag = collect([
            'id' => 1,
        ]);
        broadcast(new FleetKeysUpdate($flag));
    }

    public function removeFleet(Request $request)
    {
        $k = KeyFleetJoin::where('fleet_type_id', $request->fleet_type_id)->where('key_type_id', $request->key_type_id)->get();
        foreach ($k as $k) {
            $k->delete();
        }
        $flag = collect([
            'id' => 1,
        ]);
        broadcast(new FleetKeysUpdate($flag));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KeyFleetJoinController  $keyFleetJoinController
     * @return \Illuminate\Http\Response
     */
    public function show(KeyFleetJoinController $keyFleetJoinController)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\KeyFleetJoinController  $keyFleetJoinController
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KeyFleetJoinController $keyFleetJoinController)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KeyFleetJoinController  $keyFleetJoinController
     * @return \Illuminate\Http\Response
     */
    public function destroy(KeyFleetJoinController $keyFleetJoinController)
    {
        //
    }
}

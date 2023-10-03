<?php

namespace App\Http\Controllers;

use App\Events\StagingSystemUpdate;
use App\Models\StagingSystem;
use Illuminate\Http\Request;

class StagingSystemContoller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = StagingSystem::with([
            'system:id,constellation_id,system_name',
            'system.constellation:id,constellation_name,region_id',
            'system.constellation.region:id,region_name',
            'system.webway',
            'dscan'
        ])->get();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $newStagingSystem = new StagingSystem();
        $newStagingSystem->system_id = $request->system_id;
        $newStagingSystem->name = $request->name;
        $newStagingSystem->save();
        $data = StagingSystem::with(
            'system:id,constellation_id,system_name',
            'system.constellation:id,constellation_name,region_id',
            'system.constellation.region:id,region_name',
            'system.webway',
            'dscan'
        )->whereId($newStagingSystem->id)->first();
        $flag = collect([
            'flag' => 1,
            'message' => $data
        ]);
        broadcast(new StagingSystemUpdate($flag));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = StagingSystem::with(
            'system:id,constellation_id,system_name',
            'system.constellation:id,constellation_name,region_id',
            'system.constellation.region:id,region_name',
            'system.webway',
            'dscan'
        )->whereId($id)->first();
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        StagingSystem::whereId($id)->delete();
        $flag = collect([
            'flag' => 2,
            'message' => $id
        ]);
        broadcast(new StagingSystemUpdate($flag));
    }
}

<?php

namespace App\Http\Controllers;

use App\Events\StationSheetUpdate;
use App\Models\System;
use App\Models\WebWay;
use App\Models\WebWayStartSystem;
use Illuminate\Http\Request;

class WebWayStartSystemsContorller extends Controller
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

    public function getSystemList()
    {
        $webwaySystemIDs = WebWayStartSystem::where('system_id', '!=', 30004759)->pluck('system_id');
        if ($webwaySystemIDs) {
            $systems = System::whereIn('id', $webwaySystemIDs)->select(['id as value', 'system_name as text'])->get();

            return ['systems' => $systems];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        WebWayStartSystem::create(['system_id' => $request->system_id]);
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
    public function update(Request $request)
    {
        $ids = [];
        $system_ids = $request->system_ids;
        foreach ($system_ids as $pull) {
            array_push($ids, $pull['value']);
        }

        $w = WebWay::whereNotIn('start_system_id', $ids)->get();
        foreach ($w as $w) {
            $w->delete();
        }
        $w = WebWayStartSystem::whereNotIn('system_id', $ids)->get();
        foreach ($w as $w) {
            $w->delete();
        }
        foreach ($ids as $system_id) {
            WebWayStartSystem::updateOrCreate(
                ['system_id' => $system_id]
            );
        }

        $flag = collect([
            'flag' => 1,
        ]);
        broadcast(new StationSheetUpdate($flag));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $w = WebWayStartSystem::where('system_id', $id)->get();
        foreach ($w as $w) {
            $w->delete();
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Jobs\getWebwayJob;
use Illuminate\Http\Request;

class WebWayController extends Controller
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

    public function getWebWay(Request $request)
    {
        $system_id = $request['system_id'];
        $link = $request['link'];
        $jumps = $request['jumps'];
        $link_p = $request['link_p'];
        $jumps_p = $request['jumps_p'];
        $start_system_id = $request['start_system_id'];

        getWebwayJob::dispatch(
            $start_system_id,
            $system_id,
            $link,
            $jumps,
            $link_p,
            $jumps_p
        )->onQueue('webway');
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

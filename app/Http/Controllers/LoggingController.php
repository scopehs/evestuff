<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasPermissions;

class LoggingController extends Controller
{
    use HasPermissions;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];

        return ['logs' => $data];
    }

    public function logCampaign($campid)
    {
        // dd($campid);
        $data = [];

        return ['logs' => $data];
    }

    public function rcSheetLogging()
    {
        // dd($campid);
        $check = Auth::user();
        if ($check->can('view_admin_logs')) {
            $data = [];

            return ['logs' => $data];
        } else {
            return ['logs' => null];
        }
    }

    public function stationLogging()
    {
        // dd($campid);

        $data = [];

        return ['logs' => $data];
    }

    public function logAdmin()
    {
        // dd($campid);
        $data = [];

        return ['logs' => $data];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function nodeAdd(Request $request, $campid)
    {
    }

    public function nodeDelete(Request $request, $campid)
    {
    }

    public function joinleaveCampaign($campid, $charid, $logtype)
    {
    }

    public function lastchecked(Request $request, $campid)
    {
    }

    public function systemscout(Request $request, $campid)
    {
    }

    public function addremovechar(Request $request, $campid)
    {
    }

    public function addRemoveRoles(Request $request)
    {
    }

    public function nodeDeleteMulti(Request $request, $campid)
    {
    }

    public function mjoinleaveCampaign($campid, $charid, $logtype)
    {
    }

    public function nodeAddMulti(Request $request, $campid)
    {
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

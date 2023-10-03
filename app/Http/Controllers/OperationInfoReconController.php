<?php

namespace App\Http\Controllers;

use App\Models\OperationInfoRecon;
use App\Models\OperationInfoSystemRecon;
use Illuminate\Http\Request;

class OperationInfoReconController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = operationReconAll();
        return ['recon' => $data];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $done =  checkUserNameRecon($request->name, $request->user_id, $request->opID);

        if (!$done) {
            return response()->json([
                'status' => true,
                'message' => 'user not found',
                'errors' => 'all'
            ], 201);
        }
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

    public function removeFromOp(Request $request, $id)
    {
        $oldFleetID = null;
        $oldSystemID = null;
        $recon = OperationInfoRecon::where('id', $request->id)->first();
        $checkSystemRecon = OperationInfoSystemRecon::where('operation_info_recon_id', $recon->id)->first();
        if ($checkSystemRecon) {
            $oldSystemID = $checkSystemRecon->operation_info_system_id;
        }
        $oldOpID = $recon->operation_info_id;
        $recon->operation_info_id = null;

        if ($recon->operation_info_fleet_id) {
            $oldFleetID = $recon->operation_info_fleet_id;
        }
        $recon->operation_info_fleet_id = null;

        if ($recon->system_id) {
            $oldSystemID = $recon->system_id;
        }
        $recon->system_id = null;


        $recon->operation_info_recon_status_id = 1;
        $recon->role_id = 0;
        $recon->online = 0;
        $recon->dead = 0;
        $recon->save();



        operationReconSoloBcast($recon->id, 5);
        operationReconRemoveSoloBcast($recon->id, $oldOpID, 13);
        if ($oldFleetID) {
            operationInfoSoloPageFleetBroadcast($oldFleetID, $oldOpID, 2);
        }
        if ($oldSystemID) {
            OperationInfoSystemRecon::where('operation_info_recon_id', $recon->id)
                ->where('operation_info_system_id', $oldSystemID)->delete();
            operationInfoSystemsSoloBcast($oldOpID, $oldSystemID, 14);
        }
    }

    public function updateDeadStatus(Request $request, $id)
    {
        $recon = OperationInfoRecon::where('id', $id)->first();
        $recon->dead = $request->dead;
        $recon->save();

        operationReconSoloBcast($id, 5);
    }

    public function updateOnlineStatus(Request $request, $id)
    {
        $recon = OperationInfoRecon::where('id', $id)->first();
        $recon->online = $request->online;
        $recon->save();

        operationReconSoloBcast($id, 5);
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

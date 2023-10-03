<?php

namespace App\Http\Controllers;

use App\Models\OperationInfo;
use App\Models\OperationInfoMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperationInfoSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($link)
    {
        return ['data' => operationInfoSoloPagePullLink($link)];
    }

    public function addReadBy($id)
    {

        $notes = OperationInfoMessage::whereOperationInfoId($id)->get();

        // Check if the current user's ID is not already in the "read_by" column
        foreach ($notes as $note) {
            if (!in_array(Auth::id(), $note->read_by['user_id'])) {
                $readBy = $note->read_by;
                $readBy['user_id'][] = Auth::id();
                $note->read_by = $readBy;
                $note->save();
            }
        }

        operationInfoPageMessageBroadcast($id, 7);
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


    public function messageAdd(Request $request, $id)
    {
        $new = new OperationInfoMessage();
        $new->operation_info_id = $id;
        $new->user_id = Auth::id();
        $new->message = $request->message;
        $readBy['user_id'][] = Auth::id();
        $new->read_by = $readBy;
        $new->save();
        operationInfoPageMessageBroadcast($id, 7);
    }

    public function messageDelete($id)
    {
        $message = OperationInfoMessage::where('id', $id)->first();
        $opID = $message->operation_info_id;
        $message->delete();
        operationInfoPageMessageBroadcast($opID, 7);
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

        $operation = OperationInfo::where('id', $id)->first();
        $operation->form_op_allies_ready = intval($request->form_op_allies_ready);
        $operation->form_op_blackhand_ready = intval($request->form_op_blackhand_ready);
        $operation->form_op_capital_fc_ready = intval($request->form_op_capital_fc_ready);
        $operation->form_op_fc_ready = intval($request->form_op_fc_ready);
        $operation->form_op_gsfoe_ready = intval($request->form_op_gsfoe_ready);
        $operation->form_op_gsol_ready = intval($request->form_op_gsol_ready);
        $operation->form_op_recon_ready = intval($request->form_op_recon_ready);
        $operation->form_op_scouts_ready = intval($request->form_op_scouts_ready);
        $operation->planing_op_allies_infored = intval($request->planing_op_allies_infored);
        $operation->planing_op_capital_fc_found = intval($request->planing_op_capital_fc_found);
        $operation->planing_op_doctromes_decoded = intval($request->planing_op_doctromes_decoded);
        $operation->planing_op_fc_found = intval($request->planing_op_fc_found);
        $operation->planing_op_posted = intval($request->planing_op_posted);
        $operation->planing_op_pre_ping = intval($request->planing_op_pre_ping);
        $operation->planing_op_recon_alerted = intval($request->planing_op_recon_alerted);
        $operation->post_op_coord_done = intval($request->post_op_coord_done);
        $operation->post_op_defrief_done = intval($request->post_op_defrief_done);
        $operation->post_op_fc_done = intval($request->post_op_fc_done);
        $operation->post_op_recon_done = intval($request->post_op_recon_done);
        $operation->post_op_scouts_done = intval($request->post_op_scouts_done);
        $operation->status_id = intval($request->status_id);
        $operation->fleet_table = intval($request->fleet_table);
        $operation->check_list = intval($request->check_list);
        $operation->recon_table = intval($request->recon_table);
        $operation->system_table = intval($request->system_table);

        $operation->save();
        operationInfoSoloPageBroadcast($id, 1);

        if ($operation->wasChanged('status_id')) {
            operationInfoStatusBcast(intval($request->status_id), $id, 8);
            operationInfoSoloBroadcast($id, 2);
        }
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

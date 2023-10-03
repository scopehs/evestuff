<?php

namespace App\Http\Controllers;

use App\Models\NewSystemNode;
use App\Models\NewUserNode;
use Illuminate\Http\Request;

class NewUserNodeController extends Controller
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

    public function addTimer(Request $request, $id)
    {
        $n = NewUserNode::where('id', $id)->first();

        $n->update([
            'end_time' => $request->end_time,
            'input_time' => now(),
            'base_time' => $request->base_time,
        ]);

        broadcastsystemSolo($request->system_id, 7);
        operationInfoSoloSystemBCast($request->system_id, 16);
    }

    public function addTimertoNode(Request $request, $id)
    {
        $n = NewSystemNode::where('id', $id)->first();


        $n->end_time = $request->end_time;
        $n->input_time = now();
        $n->base_time = $request->base_time;
        $n->save();

        broadcastsystemSolo($request->system_id, 7);
        operationInfoSoloSystemBCast($request->system_id, 16);
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
        $node = NewUserNode::where('id', $id)->first();
        $systemID = $node->node->system_id;
        $opUser = $node->opUser;
        $opUser->new_user_node_id = null;
        $opUser->user_status_id = 3;
        $opUser->save();
        broadcastuserOwnSolo($opUser->id, $opUser->user_id, 3, $opUser->operation_id);
        broadcastuserSolo($opUser->operation_id, $opUser->id, 6);
        $node->delete();
        broadcastsystemSolo($systemID, 7);
        operationInfoSoloSystemBCast($systemID, 16);
    }
}

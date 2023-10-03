<?php

namespace App\Http\Controllers;

use App\Models\NewSystemNode;
use App\Models\NewUserNode;
use App\Models\OperationUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewSystemNodeController extends Controller
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
        $new = new NewSystemNode();
        $new->system_id = $request->system_id;
        $new->campaign_id = $request->campaign_id;
        $new->name = $request->name;
        $new->save();
        // TODO Change this so it only gets Campaigns what are active.
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

    public function addUserToNodeAdmin(Request $request)
    {
        $primery = 0;
        $opUser = OperationUser::where('id', $request->opUserID)->first();
        $systemNode = NewSystemNode::where('id', $request->id)->first();
        if ($systemNode->node_status == 1) {
            $primery = 1;
        }

        $userNode = new NewUserNode();
        $userNode->primery = $primery;
        $userNode->node_id = $systemNode->id;
        $userNode->operation_user_id = $opUser->id;
        $userNode->node_status_id = 2;
        $userNode->save();

        $opUser->user_status_id = 4;
        $opUser->new_user_node_id = $userNode->id;
        $opUser->save();

        broadcastsystemSolo($systemNode->system_id, 7);
        operationInfoSoloSystemBCast($systemNode->system_id, 16);
        broadcastuserSolo($opUser->operation_id, $opUser->id, 6);
        broadcastuserOwnSolo($opUser->id, $opUser->user_id, 3, $opUser->operation_id);
    }

    public function addCharToNode(Request $request)
    {
        $check = NewUserNode::where('node_id', $request->node_id)
            ->count();
        $prime = 1;
        if ($check > 0) {
            $prime = 0;
        }

        $check = NewSystemNode::where('id', $request->node_id)->first();
        if ($check->node_status == 8) {
            $prime = 0;
        }

        if ($prime == 1) {
            $check->update(['node_status' => 2]);
        }
        $new = new NewUserNode();
        $new->primery = $prime;
        $new->node_id = $request->node_id;
        $new->operation_user_id = $request->op_user_id;
        $new->node_status_id = 2;
        $new->save();

        $o = OperationUser::where('id', $request->op_user_id)->get();

        foreach ($o as $o) {
            $o->update([
                'new_user_node_id' => $new->id,
                'user_status_id' => 4,
                'system_id' => $request->system_id,
            ]);
        }

        broadcastsystemSolo($request->system_id, 7);
        operationInfoSoloSystemBCast($request->system_id, 16);
        broadcastuserSolo($request->opID, $request->op_user_id, 6);
        broadcastuserOwnSolo($request->op_user_id, Auth::id(), 3, $request->opID);
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

    public function updateStatus(Request $request, $id)
    {
        $opUserID = null;
        $oldCheck = null;
        // dd($request->all());
        switch ($request->status_id) {
            case 1: // * new
                $systemNode = NewSystemNode::where('id', $id)->first();
                if ($systemNode) {
                    $systemNode->node_status = 1;
                    $new = NewUserNode::where('node_id', $systemNode->node_id)
                        ->where('primery', 0)
                        ->oldest()
                        ->first();
                    if ($new) {
                        $opUser = $new->opUser;
                        $new->primery = 1;
                        $new->timestamps = false;
                        $new->save();
                    }
                } else {
                    $userNode = NewUserNode::where('id', $id)->first();
                    $opUser = $userNode->opUser;
                    $opUser->new_user_node_id = null;
                    $opUser->user_status_id = 3;
                    $opUser->save();
                    $new = NewUserNode::where('node_id', $userNode->node_id)
                        ->where('primery', 0)
                        ->oldest()
                        ->first();
                    if ($new) {
                        $new->primery = 1;
                        $new->timestamps = false;
                        $new->save();
                        $userNode->delete();
                    } else {
                        $systemNode = NewSystemNode::where('id', $userNode->node_id)->first();
                        $systemNode->node_status = 1;
                        $systemNode->save();
                        $userNode->delete();
                    }
                }
                broadcastuserOwnSolo($opUser->id, $opUser->user_id, 3, $opUser->operation_id);
                broadcastuserSolo($opUser->operation_id, $opUser->id, 6);
                broadcastsystemSolo($request->system_id, 7);
                operationInfoSoloSystemBCast($request->system_id, 16);

                break;
            case 2: // * warm up
                $userNode = NewUserNode::where('id', $id)->first();
                $userNode->node_status_id = 2;
                $userNode->save();
                $opUser = $userNode->opUser;
                broadcastuserOwnSolo($opUser->id, $opUser->user_id, 3, $opUser->operation_id);
                broadcastuserSolo($opUser->operation_id, $opUser->id, 6);
                broadcastsystemSolo($request->system_id, 7);
                operationInfoSoloSystemBCast($request->system_id, 16);
                break;

            case 3: // * Hacking
                $userNode = NewUserNode::where('id', $id)->first();
                $userNode->node_status_id = 3;
                $userNode->save();
                $opUser = $userNode->opUser;
                broadcastuserOwnSolo($opUser->id, $opUser->user_id, 3, $opUser->operation_id);
                broadcastuserSolo($opUser->operation_id, $opUser->id, 6);
                broadcastsystemSolo($request->system_id, 7);
                operationInfoSoloSystemBCast($request->system_id, 16);
                break;

            case 4: // * Success
                $systemNode = NewSystemNode::where('id', $id)->first();
                if ($systemNode) {
                    $userNodes = NewUserNode::where('node_id', $id)->get();
                } else {
                    $userNode = NewUserNode::where('id', $id)->first();
                    $systemNode = NewSystemNode::where('id', $userNode->node_id)->first();
                    $userNodes = NewUserNode::where('node_id', $userNode->node_id)->get();
                }

                $systemNode->node_status = 4;
                $systemNode->save();
                foreach ($userNodes as $node) {
                    $opUser = $node->opUser;
                    $opUser->new_user_node_id = null;
                    $opUser->user_status_id = 3;
                    $opUser->save();
                    $node->delete();
                    broadcastuserOwnSolo($opUser->id, $opUser->user_id, 3, $opUser->operation_id);
                    broadcastuserSolo($opUser->operation_id, $opUser->id, 6);
                }

                broadcastsystemSolo($request->system_id, 7);
                operationInfoSoloSystemBCast($request->system_id, 16);

                break;

            case 5: // * Failed
                $systemNode = NewSystemNode::where('id', $id)->first();
                if ($systemNode) {
                    $userNodes = NewUserNode::where('node_id', $id)->get();
                } else {
                    $userNode = NewUserNode::where('id', $id)->first();
                    $systemNode = NewSystemNode::where('id', $userNode->node_id)->first();
                    $userNodes = NewUserNode::where('node_id', $userNode->node_id)->get();
                }

                $systemNode->node_status = 5;
                $systemNode->save();
                foreach ($userNodes as $node) {
                    $opUser = $node->opUser;
                    $opUser->new_user_node_id = null;
                    $opUser->user_status_id = 3;
                    $opUser->save();
                    $node->delete();
                    broadcastuserOwnSolo($opUser->id, $opUser->user_id, 3, $opUser->operation_id);
                    broadcastuserSolo($opUser->operation_id, $opUser->id, 6);
                }

                broadcastsystemSolo($request->system_id, 7);
                operationInfoSoloSystemBCast($request->system_id, 16);

                break;
            case 6: // * Pushed off
                $node = NewUserNode::where('id', $id)->first();
                if ($node) {
                    $opUser = $node->opUser;
                    $systemID = $node->node->system_id;
                    if ($node->primery == 1) {
                        $new = NewUserNode::where('node_id', $node->node_id)->where('primery', 0)->oldest()->first();
                        if ($new) {
                            $new->primery = 1;
                            $new->timestamps = false;
                            $new->save();
                            $opUser->new_user_node_id = null;
                            $opUser->user_status_id = 3;
                            $opUser->save();
                            $node->delete();
                        } else {
                            $systemNode = $node->node;
                            $systemNode->node_status = 6;
                            $systemNode->end_time = null;
                            $systemNode->input_time = null;
                            $systemNode->base_time = null;
                            $systemNode->save();
                            $opUser->new_user_node_id = null;
                            $opUser->user_status_id = 3;
                            $opUser->save();
                            $node->delete();
                        }
                    } else {
                        $opUser->new_user_node_id = null;
                        $opUser->user_status_id = 3;
                        $opUser->save();
                        $node->delete();
                    }
                    broadcastuserOwnSolo($opUser->id, $opUser->user_id, 3, $opUser->operation_id);
                    broadcastuserSolo($opUser->operation_id, $opUser->id, 6);
                    broadcastsystemSolo($systemID, 7);
                    operationInfoSoloSystemBCast($systemID, 16);
                } else {
                    $systemNode = NewSystemNode::where('id', $id)->first();
                    $new = NewUserNode::where('node_id', $systemNode->id)->oldest()->first();
                    if ($new) {
                        $new->primery = 1;
                        $new->save();
                        $opUser = $new->opUser;

                        $systemNode->node_status = 2;
                        $systemNode->end_time = null;
                        $systemNode->input_time = null;
                        $systemNode->save();

                        broadcastuserOwnSolo($opUser->id, $opUser->user_id, 3, $opUser->operation_id);
                        broadcastuserSolo($opUser->operation_id, $opUser->id, 6);

                        broadcastsystemSolo($systemNode->system_id, 7);
                        operationInfoSoloSystemBCast($systemNode->system_id, 16);
                    }
                }
                break;
            case 7: // * Hostile Hacking
                $systemNode = NewSystemNode::where('id', $id)->first();
                if ($systemNode) {
                    $systemNode->node_status = 7;
                    $systemNode->save();
                    $userNodes = NewUserNode::where('node_id', $id)->get();
                    foreach ($userNodes as $userNode) {
                        $opUser = $userNode->opUser;
                        $opUser->new_user_node_id = null;
                        $opUser->user_status_id = 3;
                        $opUser->save();
                        $userNode->delete();
                        broadcastuserOwnSolo($opUser->id, $opUser->user_id, 3, $opUser->operation_id);
                        broadcastuserSolo($opUser->operation_id, $opUser->id, 6);
                    }
                    broadcastsystemSolo($systemNode->system_id, 7);
                    operationInfoSoloSystemBCast($systemNode->system_id, 16);
                } else {
                    $userNode = NewUserNode::where('id', $id)->first();
                    $systemNode = NewSystemNode::where('id', $userNode->node_id)->first();
                    $systemNode->node_status = 7;
                    $userNodes = NewUserNode::where('node_id', $userNode->node_id)->get();
                    foreach ($userNodes as $userNode) {
                        $opUser = $userNode->opUser;
                        $opUser->new_user_node_id = null;
                        $opUser->user_status_id = 3;
                        $opUser->save();
                        $userNode->delete();
                        broadcastuserOwnSolo($opUser->id, $opUser->user_id, 3, $opUser->operation_id);
                        broadcastuserSolo($opUser->operation_id, $opUser->id, 6);
                    }
                    broadcastsystemSolo($systemNode->system_id, 7);
                    operationInfoSoloSystemBCast($systemNode->system_id, 16);
                }
                break;
            case 8: // * Friendly Hacking
                $SystemNode = NewSystemNode::where('id', $id)->first();
                $SystemNode->node_status = 8;
                $SystemNode->save();
                broadcastsystemSolo($SystemNode->system_id, 7);
                operationInfoSoloSystemBCast($SystemNode->system_id, 16);
                break;
            case 9: // * Passive
                $systemNode = NewSystemNode::where('id', $id)->first();
                if ($systemNode) {
                    $systemNode->node_status = 9;
                    $systemNode->save();
                    $userNodes = NewUserNode::where('node_id', $systemNode->id)->get();
                    foreach ($userNodes as $userNode) {
                        $opUser = $userNode->opUser;
                        $opUser->new_user_node_id = null;
                        $opUser->user_status_id = 3;
                        $opUser->save();
                        $userNode->delete();
                        broadcastuserOwnSolo($opUser->id, $opUser->user_id, 3, $opUser->operation_id);
                        broadcastuserSolo($opUser->operation_id, $opUser->id, 6);
                    }
                    broadcastsystemSolo($request->system_id, 7);
                    operationInfoSoloSystemBCast($request->system_id, 16);
                } else {
                    $userNode = NewUserNode::where('id', $id)->first();
                    $systemNode = NewSystemNode::where('id', $userNode->node_id)->first();
                    $systemNode->node_status = 9;
                    $systemNode->save();
                    $userNodes = NewUserNode::where('node_id', $userNode->node_id)->get();
                    foreach ($userNodes as $userNode) {
                        $opUser = $userNode->opUser;
                        $opUser->new_user_node_id = null;
                        $opUser->user_status_id = 3;
                        $opUser->save();
                        $userNode->delete();
                        broadcastuserOwnSolo($opUser->id, $opUser->user_id, 3, $opUser->operation_id);
                        broadcastuserSolo($opUser->operation_id, $opUser->id, 6);
                    }
                    broadcastsystemSolo($request->system_id, 7);
                    operationInfoSoloSystemBCast($request->system_id, 16);
                }
                break;
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
        $systemNode = NewSystemNode::where('id', $id)->first();
        if ($systemNode) {
            $system_id = $systemNode->system_id;
            $nodes = NewUserNode::where('node_id', $id)->get();
            foreach ($nodes as $node) {
                $OpUser = $node->opUser;
                $OpUser->new_user_node_id = null;
                $OpUser->user_status_id = 3;
                $OpUser->save();
                $node->delete();

                broadcastuserOwnSolo($OpUser->id, $OpUser->user_id, 3, $OpUser->operation_id);
                broadcastuserSolo($OpUser->operation_id, $OpUser->id, 6);
            }
            // TODO Change this so it only gets Campaigns what are active.
            $systemNode->delete();
            broadcastsystemSolo($system_id, 7);
            operationInfoSoloSystemBCast($system_id, 16);
        }
    }
}

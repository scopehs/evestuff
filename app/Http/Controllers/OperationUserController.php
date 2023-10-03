<?php

namespace App\Http\Controllers;

use App\Events\OperationUpdate;
use App\Models\OperationUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperationUserController extends Controller
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

    public function edit(Request $request, $opUserID, $opID)
    {
        $opUser = OperationUser::where('id', $opUserID)->first();
        $opUser->name = $request->name;
        $opUser->entosis = $request->entosis;
        $opUser->ship = $request->ship;
        $opUser->role_id = $request->role_id;
        $opUser->save();
        $userID = $opUser->user_id;

        broadcastuserOwnSolo($opUserID, $userID, 3, $opID);
        broadcastuserSolo($opID, $opUserID, 6);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $opID, $userid)
    {

        $new = OperationUser::create($request->all());
        if (Auth::id() == $userid) {
            broadcastuserOwnSolo($new->id, $userid, 3, $opID);
        }


        broadcastuserSolo($opID, $new->id, 6);
    }

    public function updateOnTheWayReadyToGO(Request $request, $opID, $opUserID)
    {
        $opUser = OperationUser::where('id', $opUserID)->first();
        $oldSystemID = $opUser->system_id;
        $opUser->update($request->all());

        if ($oldSystemID) {
            broadcastsystemSolo($oldSystemID, 7);
            operationInfoSoloSystemBCast($oldSystemID, 16);
        }

        broadcastuserOwnSolo($opUserID, $opUser->user_id, 3, $opID);
        broadcastuserSolo($opID, $opUserID, 6);
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

    public function updateadd(Request $request, $id, $opID, $userid)
    {
        //TODO remove from other operations and nodes in there to a new operations

        $n = OperationUser::where('id', $id)->get();
        foreach ($n as $n) {
            $n->update($request->all());
        }

        if (Auth::id() == $userid) {
            broadcastuserOwnSolo($id, $userid, 3, $opID);
        }
        if (Auth::id() == $userid) {
            broadcastuserSolo($opID, $id, 6);
        }
        // TODO Add baordcsat to update stuff
    }

    public function updateremove(Request $request, $id, $opID, $userid)
    {

        //TODO remove from other operations and nodes in there to a new operations

        $n = OperationUser::where('id', $id)->get();
        foreach ($n as $n) {
            $n->update($request->all());
        }
        // TODO Add boradcast to update info
        if (Auth::id() == $userid) {
            broadcastuserOwnSolo($id, $userid, 3, $opID);
        }

        $flag = collect([
            'flag' => 5,
            'id' => $opID,
            'userid' => $id,
        ]);

        broadcast(new OperationUpdate($flag));
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
    public function destroy($id, $opID, $userid)
    {
        OperationUser::destroy($id);

        $flag = collect([
            'flag' => 5,
            'userid' => $id,
            'id' => $opID,
        ]);

        broadcast(new OperationUpdate($flag));

        if (Auth::id() == $userid) {
            broadcastuserOwnSolo($id, $userid, 5, $opID);
        }

        // TODO Sort out boardcasting
    }
}

<?php

namespace App\Http\Controllers;

use App\Events\WatchListStationPageUpdate;
use App\Models\StationNotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StationNotesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

    }


    public function addReadBy($id)
    {

        $notes = StationNotes::whereStationId($id)->get();

        // Check if the current user's ID is not already in the "read_by" column
        foreach ($notes as $note) {
            if (!in_array(Auth::id(), $note->read_by['user_id'])) {
                $readBy = $note->read_by;
                $readBy['user_id'][] = Auth::id();
                $note->read_by = $readBy;
                $note->save();
            }
        }

        $message = StationRecordsSolo(6, $id);

        if ($message) {
            $watchListIDs = getStationWatchListIDs($message->id);
            foreach ($watchListIDs as $watchListID) {
                $flag = collect([
                    'flag' => 1,
                    'message' => $message,
                    'id' => $watchListID,
                ]);
                broadcast(new WatchListStationPageUpdate($flag));
            }
        }
    }



    /**
     * Store a newly created messages and links them to the stataion.
     */
    public function updateMessage(Request $request, $id)
    {

        $newMessage = new StationNotes();
        $newMessage->station_id = $id;
        $newMessage->user_id = Auth::id();
        $newMessage->message = $request->message;
        $readBy = $newMessage->read_by;
        $readBy['user_id'][] = Auth::id();
        $newMessage->read_by = $readBy;
        $newMessage->save();

        $message = StationRecordsSolo(6, $id);

        if ($message) {
            $watchListIDs = getStationWatchListIDs($message->id);
            foreach ($watchListIDs as $watchListID) {
                $flag = collect([
                    'flag' => 1,
                    'message' => $message,
                    'id' => $watchListID,
                ]);
                broadcast(new WatchListStationPageUpdate($flag));
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy($id)
    {
        $message = StationNotes::whereId($id)->first();
        $stationID = $message->station->id;
        $message->delete();
        $message = StationRecordsSolo(6, $stationID);

        if ($message) {
            $watchListIDs = getStationWatchListIDs($message->id);
            foreach ($watchListIDs as $watchListID) {
                $flag = collect([
                    'flag' => 1,
                    'message' => $message,
                    'id' => $watchListID,
                ]);
                broadcast(new WatchListStationPageUpdate($flag));
            }
        }
    }
}

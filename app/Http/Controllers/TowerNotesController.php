<?php

namespace App\Http\Controllers;

use App\Events\TowerSheetMessageUpdate;
use App\Models\TowerNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TowerNotesController extends Controller
{
    public function addReadBy($id)
    {

        $notes = TowerNote::whereTowerId($id)->get();

        // Check if the current user's ID is not already in the "read_by" column
        foreach ($notes as $note) {
            if (!in_array(Auth::id(), $note->read_by['user_id'])) {
                $readBy = $note->read_by;
                $readBy['user_id'][] = Auth::id();
                $note->read_by = $readBy;
                $note->save();
            }
        }

        $message = towerRecordSolo($id);

        if ($message) {
            $flag = collect([
                'message' => $message,
                'id' => $id,
            ]);
            broadcast(new TowerSheetMessageUpdate($flag));
        }
    }

    public function updateMessage(Request $request, $id)
    {

        $newMessage = new TowerNote();
        $newMessage->tower_id = $id;
        $newMessage->user_id = Auth::id();
        $newMessage->message = $request->message;
        $readBy = $newMessage->read_by;
        $readBy['user_id'][] = Auth::id();
        $newMessage->read_by = $readBy;
        $newMessage->save();

        $message = towerRecordSolo($id);

        if ($message) {
            $flag = collect([
                'message' => $message,
                'id' => $id,
            ]);

            // dd($request, $id, $flag);
            broadcast(new TowerSheetMessageUpdate($flag));
        }
    }

    public function destroy($id)
    {
        $message = TowerNote::whereId($id)->first();
        $towerID = $message->tower->id;
        $message->delete();
        $message = towerRecordSolo($towerID);

        if ($message) {
            $flag = collect([
                'message' => $message,
                'id' => $id,
            ]);

            // dd($request, $id, $flag);
            broadcast(new TowerSheetMessageUpdate($flag));
        }
    }
}

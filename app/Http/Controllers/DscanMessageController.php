<?php

namespace App\Http\Controllers;

use App\Events\dScanSoloUpdate;
use App\Models\Dscan;
use App\Models\DscanNote;
use Auth;
use Illuminate\Http\Request;

class DscanMessageController extends Controller
{
    public function addReadBy($id)
    {

        $notes = DscanNote::whereDscanId($id)->with('user')->get();
        $dscan = Dscan::whereId($id)->first();
        $link = $dscan->link;

        // Check if the current user's ID is not already in the "read_by" column
        foreach ($notes as $note) {
            if (!in_array(Auth::id(), $note->read_by['user_id'])) {
                $readBy = $note->read_by;
                $readBy['user_id'][] = Auth::id();
                $note->read_by = $readBy;
                $note->save();
            }
        }

        $message = DscanNote::whereDscanId($id)->with('user')->orderBy('created_at', "desc")->get();

        if ($message) {
            $flag = collect([
                'flag' => 12,
                'message' => $message,
                'id' => $link,
            ]);
            broadcast(new dScanSoloUpdate($flag));
        }
    }

    public function updateMessage(Request $request, $id)
    {
        $dscan = Dscan::whereId($id)->first();
        $link = $dscan->link;
        $newMessage = new DscanNote();
        $newMessage->dscan_id = $id;
        $newMessage->user_id = Auth::id();
        $newMessage->message = $request->message;
        $readBy = $newMessage->read_by;
        $readBy['user_id'][] = Auth::id();
        $newMessage->read_by = $readBy;
        $newMessage->save();

        $message = DscanNote::whereDscanId($id)->with('user')->orderBy('created_at', "desc")->get();

        if ($message) {
            $flag = collect([
                'flag' => 12,
                'message' => $message,
                'id' => $link,
            ]);

            // dd($request, $id, $flag);
            broadcast(new dScanSoloUpdate($flag));
        }
    }

    public function destroy($id)
    {

        $message = DscanNote::whereId($id)->first();
        $dscanID = $message->dscan->id;
        $dscan = Dscan::whereId($dscanID)->first();
        $link = $dscan->link;
        $message->delete();
        $message = DscanNote::whereDscanId($dscanID)->with('user')->orderBy('created_at', "desc")->get();

        if ($message) {
            $flag = collect([
                'flag' => 12,
                'message' => $message,
                'id' => $link,
            ]);

            // dd($request, $id, $flag);
            broadcast(new dScanSoloUpdate($flag));
        }
    }
}

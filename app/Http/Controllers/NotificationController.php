<?php

namespace App\Http\Controllers;

use App\Events\NotificationChanged;
use App\Models\Notification;
use App\Models\NotificationRecords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $data = getAllNotification();
        return response()->json($data);
    }

    public function test($id)
    {
        $data = reconPull($id);

        if (array_key_exists('str_structure_id_md5', $data)) {
            // echo "RECON";
        }
        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
    }

    public function update(Request $request, $id)
    {

        $n = Notification::whereId($id)->first();
        $n->update($request->all());
        $n->user_id = Auth::id();
        $n->save();
        $message = getSoloNotification($id);
        broadcast(new NotificationChanged($message));
    }
}

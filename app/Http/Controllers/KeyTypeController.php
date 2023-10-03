<?php

namespace App\Http\Controllers;

use App\Events\FleetKeysUpdate;
use App\Models\KeyFleetJoin;
use App\Models\KeyType;
use App\Models\User;
use App\Models\UserKeyJoin;
use Illuminate\Http\Request;

class KeyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['keys' => KeyType::where('id', '>', 0)->select('id', 'name')->orderBy('name', 'asc')->get()];
    }

    public function getAllUsersKeys()
    {
        return ['userskeys' => User::with('keys')->select('id', 'name', 'fc_notes')->get()];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $new = new KeyType();
        $new->name = $request->name;
        $new->save();
        $flag = collect([
            'id' => 1,
        ]);
        broadcast(new FleetKeysUpdate($flag));
    }

    public function removeKey(Request $request)
    {
        $u = UserKeyJoin::where('key_type_id', $request->key_type_id)->where('user_id', $request->user_id)->get();
        foreach ($u as $u) {
            $u->delete();
        }
        $flag = collect([
            'id' => 1,
        ]);
        broadcast(new FleetKeysUpdate($flag));
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
        $k = KeyType::find($id)->get();
        foreach ($k as $k) {
            $k->delete();
        }
        $k = UserKeyJoin::where('key_type_id', $id)->get();
        foreach ($k as $k) {
            $k->delete();
        }
        $k = KeyFleetJoin::where('key_type_id', $id)->get();
        foreach ($k as $k) {
            $k->delete();
        }
        $flag = collect([
            'id' => 1,
        ]);
        broadcast(new FleetKeysUpdate($flag));
    }
}

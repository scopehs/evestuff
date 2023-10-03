<?php

namespace App\Http\Controllers;

use App\Events\FleetKeysUpdate;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
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
    public function updateMessage(Request $request, $id)
    {
        $u = User::where('id', $id)->get();
        foreach ($u as $u) {
            $u->update($request->all());
        }
        $message = User::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
            'id' => $id,
        ]);

        // dd($request, $id, $flag);
        broadcast(new FleetKeysUpdate($flag));
    }

    public function update(Request $request, $id)
    {
        $u = User::where('id', $id)->get();
        foreach ($u as $u) {
            $u->update($request->all());
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

<?php

namespace App\Http\Controllers;

use App\Models\Moon;
use Illuminate\Http\Request;

class MoonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $moon = Moon::all();

        return ['moons' => $moon];
    }

    public function bySystem($sysid)
    {
        $moonlist = [];
        $moons = Moon::where('system_id', $sysid)->get();
        foreach ($moons as $moon) {
            $data = [
                'text' => $moon->name,
                'value' => $moon->id,
            ];

            array_push($moonlist, $data);
        }

        return ['moons' => $moonlist];
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
        //
    }
}

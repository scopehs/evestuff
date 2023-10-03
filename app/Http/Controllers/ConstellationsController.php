<?php

namespace App\Http\Controllers;

use App\Models\Constellation;
use Illuminate\Http\Request;

class ConstellationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Constellation::all();

        return ['constellationlist' => $data];
    }

    public function constellationlist()
    {
        $data = [];
        $start = Constellation::all();
        $pull = $start->sortBy('constellation_name');
        foreach ($pull as $pull) {
            $data1 = [];
            $data1 = [
                'text' => $pull['constellation_name'],
                'value' => $pull['id'],
            ];

            array_push($data, $data1);
        }

        // dd($data);

        return ['constellationlist' => $data];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

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

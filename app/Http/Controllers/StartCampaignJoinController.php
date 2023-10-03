<?php

namespace App\Http\Controllers;

use App\Models\Constellation;
use App\Models\StartCampaignJoins;
use Illuminate\Http\Request;

class StartCampaignJoinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = [];
        $pulls = StartCampaignJoins::all();
        foreach ($pulls as $pull) {
            $constellations = Constellation::where('id', $pull['constellation_id'])->get();
            $count = $constellations->count();
            if ($count != 0) {
                foreach ($constellations as $con) {
                    $data = [];
                    $data = [
                        'constellation_id' => $pull['constellation_id'],
                        'start_campaign_id' => $pull['start_campaign_id'],
                        'id' => $pull['id'],
                        'constellation_name' => $con['constellation_name'],
                    ];
                }
                array_push($list, $data);
            }
        }

        return ['value' => $list];
    }

    public function indexByID($campid)
    {
        $list = [];
        $pulls = StartCampaignJoins::all();
        foreach ($pulls as $pull) {
            $constellations = Constellation::where('id', $pull['constellation_id'])->get();
            $count = $constellations->count();
            if ($count != 0) {
                foreach ($constellations as $con) {
                    $data = [];
                    $data = [
                        'constellation_id' => $pull['constellation_id'],
                        'start_campaign_id' => $pull['start_campaign_id'],
                        'id' => $pull['id'],
                        'constellation_name' => $con['constellation_name'],
                    ];
                }
                array_push($list, $data);
            }
        }

        return ['value' => $list];
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

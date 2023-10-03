<?php

namespace App\Http\Controllers;

use App\Events\CampaignSystemUpdate;
use App\Models\Campaign;
use App\Models\CampaignRecords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class CampaignRecordsController extends Controller
{
    use HasRoles;
    use HasPermissions;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('use_reserved_connection')) {
            return ['campaigns' => CampaignRecords::with(
                [
                    'webway' => function ($t) {
                        $t->where('permissions', 1);
                    },
                    'priority',
                ]
            )->get()];
        } else {
            return ['campaigns' => CampaignRecords::with([
                'webway' => function ($t) {
                    $t->where('permissions', 1);
                },
                'priority',
            ])
                ->get(), ];
        }
    }

    public function campaignslist()
    {
        $data = [];
        $pull = CampaignRecords::where('status_id', '<', 3)->orderBy('start', 'asc')->get();
        foreach ($pull as $pull) {
            $data1 = [];
            $data1 = [
                'text' => $pull['region'].' - '.$pull['constellation'].' - '.$pull['system'].' - '.$pull['alliance'].' - '.$pull['item_name'].' - '.$pull['start'],
                'value' => $pull['id'],
            ];

            array_push($data, $data1);
        }

        // dd($data);

        return ['campaignslist' => $data];
    }

    public function campaignslistRegion()
    {
        $data = [];
        $pull = CampaignRecords::where('status_id', '<', 3)->orderBy('start', 'asc')->get();
        $pull = $pull->unique('region_id');
        $pull = $pull->sortBy('region');
        foreach ($pull as $pull) {
            $data1 = [];
            $data1 = [
                'text' => $pull['region'],
                'value' => $pull['region_id'],
            ];

            array_push($data, $data1);
        }

        // dd($data);

        return ['campaignslistRegion' => $data];
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
        return ['campaign' => Campaign::where('id', $id)];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request#
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $c = Campaign::find($id)->get();
        foreach ($c as $c) {
            $c->update($request->all());
        }
        $flag = collect([
            'flag' => 4,
            'id' => $id,
        ]);
        broadcast(new CampaignSystemUpdate($flag));
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

<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignSystemRecords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignSystemRecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['systems' => CampaignSystemRecords::all()];
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
     * Display the cccccspecified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    public function updatePriority(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->can('edit_hack_priority')) {
            $c = Campaign::where('id', $id)->get();
            foreach ($c as $c) {
                $c->update(['priority' => $request->priority]);
            }
        }
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
        $c = CampaignSystemRecords::find($id)->get();
        foreach ($c as $c) {
            $c->update($request->all());
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

<?php

namespace App\Http\Controllers;

use App\Events\AffilationUpdate;
use App\Models\Affiliation;
use App\Models\Alliance;
use Illuminate\Http\Request;

class AffilationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Affiliation::with('alliances')->get();
        return response()->json([
            'affiliations' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $newAffiliation = new Affiliation();
        $newAffiliation->name = $request->name;
        $newAffiliation->short_name = $request->short_name;
        $newAffiliation->color = $request->color;
        $newAffiliation->save();
        return $this->show($newAffiliation->id);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Affiliation::with('alliances')->whereId($id)->first();

        $flag = collect([
            'flag' => 1,
            'message' => $data
        ]);
        broadcast(new AffilationUpdate($flag))->toOthers();

        return response()->json([
            'affiliation' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $affiliation = Affiliation::whereId($request->id)->first();
        $affiliation->name = $request->name;
        $affiliation->short_name = $request->short_name;
        $affiliation->color = $request->color;
        $affiliation->save();
        return  $this->show($affiliation->id);
    }
    /**
     * Add Affilation ID to Alliance.
     */
    public function addAlliance(Request $request, $id)
    {
        $alliance = Alliance::whereId($request->alliance_id)->first();
        $alliance->affiliation_id = $id;
        $alliance->save();
        return  $this->show($id);
    }



    /**
     * Remove Affilation ID to Alliance.
     */
    public function removeAlliance($id)
    {
        $alliance = Alliance::whereId($id)->first();
        $affiliation_id = $alliance->affiliation_id;
        $alliance->affiliation_id = null;
        $alliance->save();

        $this->show($affiliation_id);
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Alliance::where('affiliation_id', $id)->update(['affiliation_id' => null]);
        Affiliation::whereId($id)->delete();

        $flag = collect([
            'flag' => 2,
            'message' => $id
        ]);
        broadcast(new AffilationUpdate($flag))->toOthers();
    }
}

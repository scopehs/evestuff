<?php

namespace App\Http\Controllers;

use App\Events\ReconTimerUpdate;
use App\Models\ReconTaskSystemRecords;
use App\Models\ReconTaskSystems;
use Illuminate\Http\Request;

class ReconTaskSystemController extends Controller
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
    public function update(Request $request, $id)
    {
        $r = ReconTaskSystems::find($id)->get();
        foreach ($r as $r) {
            $r->update(['user_id' => null]);
        }
        $r = ReconTaskSystems::find($id)->get();
        foreach ($r as $r) {
            $r->update($request->all());
        }
        $task_id = ReconTaskSystems::find($id)->value('recon_task_id');
        $message = ReconTaskSystemRecords::where('id', $id)->first();
        $flag = collect([
            'message' => $message,
            'id' => $task_id,
        ]);
        broadcast(new ReconTimerUpdate($flag))->toOthers();
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

<?php

namespace App\Http\Controllers;

use App\Events\ReconTaskNew;
use App\Models\ReconTasks;
use App\Models\ReconTaskSystems;
use Illuminate\Http\Request;

class ReconTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['tasks' => ReconTasks::all()];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new = ReconTasks::Create(['title' => $request->title, 'info' => $request->info, 'made_by_user_id' => $request->made_by_user_id]);
        $id = $new->id;
        foreach ($request->systems as $system) {
            ReconTaskSystems::Create(['recon_task_id' => $id, 'system_id' => $system]);
        }
        broadcast(new ReconTaskNew());
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ReconTasks::destroy($id);
        $systems = ReconTaskSystems::where('recon_task_id', $id);
        $systems->delete();
        broadcast(new ReconTaskNew());
    }
}

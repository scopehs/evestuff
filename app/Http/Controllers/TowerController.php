<?php

namespace App\Http\Controllers;

use App\Events\TowerChanged;
use App\Events\TowerNew;
use App\Models\Item;
use App\Models\Moon;
use App\Models\System;
use App\Models\Tower;
use App\Models\TowerItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TowerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return ['towers' => towerRecordAll()];
    }

    public function getMoons($id)
    {
        $moon = Moon::whereSystemId($id)->get();
        $moonList = $moon->map(function ($items) {
            $data['value'] = $items->id;
            $data['text'] = $items->name;

            return $data;
        });

        return [
            'moonList' => $moonList
        ];
    }

    public function towerTypeList()
    {


        $systems = System::all();
        $systemList = $systems->map(function ($items) {
            $data['value'] = $items->id;
            $data['text'] = $items->system_name;

            return $data;
        });


        $typeIDs = [
            12235, 20059, 20060, 27539, 27530,
            27589, 27592, 16213, 20061,
            20062, 27532, 27591, 27594,
            27540, 27609, 27612, 27535,
            27597, 27600, 25375, 12236,
            20063, 20064, 27533, 27595,
            27598, 16214, 20065, 20066,
            22709, 27780, 27782, 27784,
            27536, 27601, 27604, 27538,
            27603, 27606, 27786, 27788, 27790
        ];
        $towerTypes = Item::whereIn('id', $typeIDs)->get();
        $list = $towerTypes->map(function ($items) {
            $data['value'] = $items->id;
            $data['text'] = $items->item_name;

            return $data;
        });

        return [
            'towerList' => $list,
            'systemList' => $systemList
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $new = new Tower();
        $new->corp_id = $request->corp_id;
        $new->item_id = $request->item_id;
        $new->moon_id = $request->moon_id;
        $new->tower_status_id = $request->tower_status_id;
        $new->user_id = Auth::id();
        $new->save();

        $flag = collect([
            'flag' => 1,
        ]);
        broadcast(new TowerNew($flag));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tower = towerRecordSolo($id);
    }

    public function updateStatus(Request $request, $id)
    {

        $tower = Tower::whereId($id)->first();
        $tower->tower_status_id = $request->status_id;
        $tower->out_time = null;
        $tower->save();

        $message = towerRecordSolo($id);

        $flag = collect([
            'flag' => 1,
            'message' => $message
        ]);

        broadcast(new TowerChanged($flag));
    }

    public function updateText(Request $request, $id)
    {

        $tower = Tower::whereId($id)->first();
        $tower->text = $request->text;
        $tower->save();

        $message = towerRecordSolo($id);

        $flag = collect([
            'flag' => 1,
            'message' => $message
        ]);

        broadcast(new TowerChanged($flag))->toOthers();
    }





    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tower = Tower::whereId($id)->first();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Add Fit to a tower.
     */
    public function addFit(Request $request, $id)
    {

        TowerItem::whereTowerId($id)->delete();
        $dscan_results = $request->text;
        $lines  = explode("\n", $dscan_results);
        $data = array();
        foreach ($lines as $line) {
            $data[] = explode("\t", $line);
        }

        foreach ($data as $row) {

            $distance_parts = explode(" ", $row[3]);
            $row[3] = str_replace(',', '', $distance_parts[0]);
            if (count($distance_parts) == 2) {
                $row[] = $distance_parts[1];
            } else {
                $row[] = 'AU';
                if (empty($row[3]) || $row[3] == "-") {
                    $row[3] = 1;
                }
            }

            $item = Item::whereId($row[0])->first();
            $catID = $item->group->category->id;
            if ($catID == 23) {

                if (($row[4] == "km" && $row[3] <= 8000) || $row[4] == "m") {

                    $newTowerItem = new TowerItem();
                    $newTowerItem->tower_id = $id;
                    $newTowerItem->item_id = $row[0];
                    $newTowerItem->user_id = Auth::id();
                    $newTowerItem->save();
                }

                $message = towerRecordSolo($id);

                $flag = collect([
                    'flag' => 1,
                    'message' => $message
                ]);

                broadcast(new TowerChanged($flag));
            }
        }
    }
}

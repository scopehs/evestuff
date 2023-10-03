<?php

namespace App\Http\Controllers;

use App\Models\StationRecords;

class CoordSheetController extends Controller
{
    public function index()
    {
        return ['stations' => StationRecords::where('show_on_coord', 1)->where('standing', '<=', 0)->get()];
    }

    public function coordSheetListItem()
    {
        $data = [];
        // $pull = StationRecords::where('show_on_coord', 1)->get();
        // $pull = $pull->unique('item_id');
        // $pull = $pull->sortBy('item_name');
        // foreach ($pull as $pull) {
        //     $data1 = [];
        //     $data1 = [
        //         'text' => $pull['item_name'],
        //         'value' => $pull['item_id'],
        //     ];

        //     array_push($data, $data1);
        // }

        // dd($   data);

        return ['coordsheetlistType' => $data];
    }

    public function coordSheetListStatus()
    {
        $data = [];
        // $pull = StationRecords::where('show_on_coord', 1)->get();
        // $pull = $pull->unique('station_status_id');
        // $pull = $pull->sortBy('station_status_name');
        // foreach ($pull as $pull) {
        //     $text = str_replace('Upcoming - ', '', $pull['station_status_name'],);
        //     $data1 = [];
        //     $data1 = [
        //         'text' => $text,
        //         'value' => $pull['station_status_id'],
        //     ];

        //     array_push($data, $data1);
        // }

        // dd($data);

        return ['coordsheetlistStatus' => $data];
    }

    public function coordSheetListRegion()
    {
        $data = [];
        // $pull = StationRecords::where('show_on_coord', 1)->get();
        // $pull = $pull->unique('region_id');
        // $pull = $pull->sortBy('region_name');
        // foreach ($pull as $pull) {
        //     $data1 = [];
        //     $data1 = [
        //         'text' => $pull['region_name'],
        //         'value' => $pull['region_id'],
        //     ];

        //     array_push($data, $data1);
        // }

        // dd($data);

        return ['coordsheetlistRegion' => $data];
    }
}

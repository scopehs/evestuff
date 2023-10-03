<?php

namespace App\Http\Controllers;

use App\Models\Alliance;

//hierhere
class AllianceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function updateAlliances()
    {
        $status = checkeve();
        if ($status == 1) {
            updateAlliances();
        }
    }

    public function allianceTickList()
    {
        $tickerlist = [];
        $tickers = Alliance::select('ticker as text', 'id as value')->get();
        // foreach ($tickers as $ticker) {
        //     $data = [];
        //     $data = [
        //         'text' => $ticker->ticker,
        //         'value' => $ticker->id
        //     ];

        //     array_push($tickerlist, $data);
        // }
        return ['allianceticklist' => $tickers];
    }
}

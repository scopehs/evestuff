<?php

namespace App\Http\Controllers;

use App\Models\System;
use App\Models\Userlogging;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    public function index()
    {
        $check = Auth::user();
        if ($check != null) {
            Userlogging::create([
                'user_id' => Auth::id(),
                'url' => 'joined',
            ]);
        }
        $url = url()->current();
        // dd($url);
        session(['url' => $url]);
        // $data = session()->all();
        // dd($data);
        return view('/home');
    }

    public function url(Request $request)
    {
        Userlogging::create(['user_id' => Auth::id(), 'url' => $request->url]);
    }

    public function test()
    {
        $system = System::all();
        //   dd($system);
        $hello = 'YO YO YO';

        return view('/test', compact('hello', 'system'));
    }

    public function saveAllianceData(Request $request)
    {
    }
}

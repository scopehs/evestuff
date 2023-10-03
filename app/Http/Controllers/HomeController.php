<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\TimersRecord;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');fefefefefefefe
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function party()
    {
    }

    public function party2()
    {
        $test = Notification::all()->unique('brand');
        $test2 = $test->toJson();
        $data = ['timers' => $test];
        $test3 = [$test];
        $data2 = ['timers' => TimersRecord::all()];
        dd($test2, $test3, $data, $data2);
    }
}

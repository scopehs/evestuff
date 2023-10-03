<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $structures = Item::where('id', 35833)
            ->orwhere('id', 47512)
            ->orwhere('id', 47513)
            ->orwhere('id', 47514)
            ->orwhere('id', 47515)
            ->orwhere('id', 47516)
            ->orwhere('id', 37610)
            ->orwhere('id', 35841)
            ->orwhere('id', 35832)
            ->orwhere('id', 35835)
            ->orwhere('id', 35826)
            ->orwhere('id', 35837)
            ->orwhere('id', 2233)
            ->orwhere('id', 47513)
            ->orwhere('id', 47514)
            ->orwhere('id', 47515)
            ->orwhere('id', 47516)
            ->orwhere('id', 35833)
            ->orwhere('id', 35834)
            ->orwhere('id', 40340)
            ->orwhere('id', 35840)
            ->orwhere('id', 37534)
            ->orwhere('id', 35825)
            ->orwhere('id', 35827)
            ->orwhere('id', 35836)->orderBy('item_name')->get();

        $list = $structures->map(function ($items) {
            $data['value'] = $items->id;
            $data['text'] = $items->item_name;

            return $data;
        });

        return ['structurelist' => $list];
    }

    public function towerlist()
    {
        $structures = Item::where('id', 12235)
            ->orwhere('id', 20059)
            ->orwhere('id', 20060)
            ->orwhere('id', 27539)
            ->orwhere('id', 27530)
            ->orwhere('id', 27589)
            ->orwhere('id', 27592)
            ->orwhere('id', 16213)
            ->orwhere('id', 20061)
            ->orwhere('id', 20062)
            ->orwhere('id', 27532)
            ->orwhere('id', 27591)
            ->orwhere('id', 27594)
            ->orwhere('id', 27540)
            ->orwhere('id', 27609)
            ->orwhere('id', 27612)
            ->orwhere('id', 27535)
            ->orwhere('id', 27597)
            ->orwhere('id', 27600)
            ->orwhere('id', 25375)
            ->orwhere('id', 12236)
            ->orwhere('id', 20063)
            ->orwhere('id', 20064)
            ->orwhere('id', 27533)
            ->orwhere('id', 27595)
            ->orwhere('id', 27598)
            ->orwhere('id', 16214)
            ->orwhere('id', 20065)
            ->orwhere('id', 20066)
            ->orwhere('id', 22709)
            ->orwhere('id', 27780)
            ->orwhere('id', 27782)
            ->orwhere('id', 27784)
            ->orwhere('id', 27536)
            ->orwhere('id', 27601)
            ->orwhere('id', 27604)
            ->orwhere('id', 27538)
            ->orwhere('id', 27603)
            ->orwhere('id', 27606)
            ->orwhere('id', 27786)
            ->orwhere('id', 27788)
            ->orwhere('id', 27790)
            ->orderBy('item_name')->get();

        $list = $structures->map(function ($items) {
            $data['value'] = $items->id;
            $data['text'] = $items->item_name;

            return $data;
        });

        return ['towerlist' => $list];
    }

    // public function index()
    // {
    //     $structures = Item::where('id', 35833)
    //         ->orwhere('id', 47512)
    //         ->orwhere('id', 47513)
    //         ->orwhere('id', 47514)
    //         ->orwhere('id', 47515)
    //         ->orwhere('id', 47516)
    //         ->orwhere('id', 12235)
    //         ->orwhere('id', 20059)
    //         ->orwhere('id', 20060)
    //         ->orwhere('id', 27539)
    //         ->orwhere('id', 37610)
    //         ->orwhere('id', 27530)
    //         ->orwhere('id', 27589)
    //         ->orwhere('id', 27592)
    //         ->orwhere('id', 16213)
    //         ->orwhere('id', 20061)
    //         ->orwhere('id', 20062)
    //         ->orwhere('id', 27532)
    //         ->orwhere('id', 27591)
    //         ->orwhere('id', 27594)
    //         ->orwhere('id', 27540)
    //         ->orwhere('id', 27609)
    //         ->orwhere('id', 27612)
    //         ->orwhere('id', 27535)
    //         ->orwhere('id', 27597)
    //         ->orwhere('id', 27600)
    //         ->orwhere('id', 25375)
    //         ->orwhere('id', 12236)
    //         ->orwhere('id', 20063)
    //         ->orwhere('id', 20064)
    //         ->orwhere('id', 27533)
    //         ->orwhere('id', 27595)
    //         ->orwhere('id', 27598)
    //         ->orwhere('id', 16214)
    //         ->orwhere('id', 20065)
    //         ->orwhere('id', 20066)
    //         ->orwhere('id', 22709)
    //         ->orwhere('id', 27780)
    //         ->orwhere('id', 27782)
    //         ->orwhere('id', 27784)
    //         ->orwhere('id', 27536)
    //         ->orwhere('id', 27601)
    //         ->orwhere('id', 27604)
    //         ->orwhere('id', 27538)
    //         ->orwhere('id', 27603)
    //         ->orwhere('id', 27606)
    //         ->orwhere('id', 27786)
    //         ->orwhere('id', 27788)
    //         ->orwhere('id', 27790)
    //         ->orwhere('id', 35841)
    //         ->orwhere('id', 35832)
    //         ->orwhere('id', 35835)
    //         ->orwhere('id', 35826)
    //         ->orwhere('id', 35837)
    //         ->orwhere('id', 2233)
    //         ->orwhere('id', 47513)
    //         ->orwhere('id', 47514)
    //         ->orwhere('id', 47515)
    //         ->orwhere('id', 47516)
    //         ->orwhere('id', 35833)
    //         ->orwhere('id', 35834)
    //         ->orwhere('id', 40340)
    //         ->orwhere('id', 35840)
    //         ->orwhere('id', 37534)
    //         ->orwhere('id', 35825)
    //         ->orwhere('id', 35827)
    //         ->orwhere('id', 35836)->orderBy('item_name')->get();

    //     $list = $structures->map(function ($items) {
    //         $data['value'] = $items->id;
    //         $data['text'] = $items->item_name;
    //         return $data;
    //     });

    //     return ['structurelist' => $list];
    // }

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
        //
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

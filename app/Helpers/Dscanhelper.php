<?php

use App\Models\Dscan;
use App\Models\DscanHistory;
use Illuminate\Support\Str;

if (!function_exists('newDscan')) {
    function newDscan($link)
    {
        $dscan = Dscan::whereLink($link)
            ->with([
                'items.item.group.category'
            ])
            ->first();

        $allItems = $dscan->items;
        $items = workOutItemNumbers($allItems, $dscan->id);
        $dscan->itemsTotals = $items['items'];
        $dscan->groupTotals = $items['groups'];
        $dscan->categoryTotals = $items['category'];
        $dscan->save();
    }
}



if (!function_exists('newLocal')) {
    function newLocal($link)
    {
        $dscan = Dscan::whereLink($link)
            ->with([
                'locals.corp.alliance.affiliation',
            ])
            ->first();



        $allLocals = $dscan->locals;
        $local = workOutLocalNumbers($allLocals);
        $dscan->corpTotal = $local['corps'];
        $dscan->allianceTotal = $local['alliances'];
        $dscan->affiliationsTotal = $local['affiliations'];
        $dscan->save();
    }
}
if (!function_exists('getDscanInfo')) {
    function getDscanInfo($link)
    {
        $dscan = Dscan::whereLink($link)
            ->with([
                'system:id,region_id,constellation_id,system_name',
                'system.region',
                'system.constellation',
                'updatedBy:id,name',
                'madeby:id,name',
                'messages.user',
                'locals.corp.alliance.affiliation',
                'history:dscan_id,link,history_count,created_at,corpsTotalNumber,alliancesTotalNumber,affiliationsTotalNumber,itemTotalsNumber,groupTotalsNumber,categoryTotalsNumber'
            ])
            ->first();

        return [
            'dscan' => $dscan,
            'corpsTotal' => $dscan->corpTotal,
            'allianceTotal' => $dscan->allianceTotal,
            'affiliationTotal' => $dscan->affiliationsTotal,
            'itemTotals' => $dscan->itemsTotals,
            'groupTotals' => $dscan->groupTotals,
            'messages' => $dscan->messages,
            'categoryTotals' => $dscan->categoryTotals,
            'history' => false,

        ];
    }
}

if (!function_exists('getDscanLocalInfo')) {
    function getDscanLocalInfo($link, $charID)
    {
        $dscan = Dscan::whereLink($link)
            ->with([
                'system:id,region_id,constellation_id,system_name',
                'system.region',
                'system.constellation',
                'updatedBy:id,name',
                'madeby:id,name',
                'locals.corp.alliance.affiliation'
            ])
            ->first();




        $allLocals = $dscan->locals;
        $local = workOutLocalNumbers($allLocals);

        $dscan->corpTotal = $local['corps'];
        $dscan->allianceTotal = $local['alliances'];
        $dscan->affiliationsTotal = $local['affiliations'];
        $dscan->save();

        $soloLocal = $allLocals->where('id', $charID)->first();
        return [
            'soloLocal' => $soloLocal,
            'corpsTotal' => $local['corps'],
            'allianceTotal' => $local['alliances'],
            'affiliationTotal' => $local['affiliations'],
        ];
    }
}


if (!function_exists('makeDscanHistoy')) {
    function makeDscanHistoy($link)
    {
        $dscan = Dscan::whereLink($link)->with([
            'system:id,region_id,constellation_id,system_name',
            'system.region',
            'system.constellation',
            'updatedBy:id,name',
            'madeby:id,name',
            'locals.corp.alliance.affiliation'
        ])
            ->first();

        $filtered = collect($dscan->corpTotal);
        $corpTotalNum = $filtered->sum('totalInSystem');

        $filtered = collect($dscan->allianceTotal);
        $allianceTotalNum = $filtered->sum('totalInSystem');

        $filtered = collect($dscan->affiliationsTotal);
        $affiliationTotalNum = $filtered->sum('totalInSystem');

        $filtered = collect($dscan->itemsTotals)->where('details.group.category.id', 6);
        $itemsTotalsNum = $filtered->sum('totalInSystem');

        $filtered = collect($dscan->categoryTotals)->whereIn('details.id', [65, 40, 22]);
        $categoryTotalsNum = $filtered->sum('totalInSystem');


        $count = DscanHistory::where('dscan_id', $dscan->id)->count() + 1;
        $newHistory = new DscanHistory();
        $newHistory->dscan_id = $dscan->id;
        $newHistory->corpsTotalNumber = $corpTotalNum;
        $newHistory->alliancesTotalNumber = $allianceTotalNum;
        $newHistory->affiliationsTotalNumber = $affiliationTotalNum;
        $newHistory->itemTotalsNumber = $itemsTotalsNum;
        $newHistory->groupTotalsNumber = $itemsTotalsNum;
        $newHistory->categoryTotalsNumber = $categoryTotalsNum;
        $newHistory->user_id = $dscan->user_id;
        $newHistory->system_id = $dscan->system_id ?? null;
        $newHistory->link = str::uuid();
        $newHistory->updated_by = $dscan->updated_by ?? null;
        $newHistory->totals = $dscan->totals;
        $newHistory->corpsTotal = $dscan->corpTotal;
        $newHistory->alliancesTotal = $dscan->allianceTotal;
        $newHistory->affiliationsTotal = $dscan->affiliationsTotal;
        $newHistory->itemTotals = $dscan->itemsTotals;
        $newHistory->groupTotals = $dscan->groupTotals;
        $newHistory->categoryTotals = $dscan->categoryTotals;
        $newHistory->dscan = $dscan;
        $newHistory->history_count = $count;
        $newHistory->save();
    }
}

if (!function_exists('loadDscanHistory')) {
    function loadDscanHistory($link)
    {
        $dscan = DscanHistory::where('link', $link)->with('messages.user')->first();
        $allHistory = DscanHistory::where('dscan_id', $dscan->dscan_id)
            ->orderBy('history_count', 'desc')
            ->select([
                'id',
                'link',
                'created_at',
                'corpsTotalNumber',
                'alliancesTotalNumber',
                'affiliationsTotalNumber',
                'itemTotalsNumber',
                'groupTotalsNumber',
                'categoryTotalsNumber'
            ])->get();



        $liveDscan = Dscan::where('id', $dscan->dscan_id)->pluck('link')->first();

        return [
            'dscan' => $dscan->dscan,
            'messages' => $dscan->messages,
            'corpsTotal' => $dscan->corpsTotal,
            'allianceTotal' => $dscan->alliancesTotal,
            'affiliationTotal' => $dscan->affiliationsTotal,
            'itemTotals' => $dscan->itemTotals,
            'groupTotals' => $dscan->groupTotals,
            'categoryTotals' => $dscan->categoryTotals,
            'history' => true,
            'allHistory' => $allHistory,
            'liveDscan' => $liveDscan,
        ];
    }
}


if (!function_exists('workOutLocalNumbers')) {
    function workOutLocalNumbers($allLocals)
    {

        $corpsTotal = [];
        $allianceTotal = [];
        $affiliationTotal = [];

        foreach ($allLocals as $local) {
            if ($local->corp) {
                $corpsTotal[$local->corp->name]['details'] = $local->corp;
                $corpsTotal[$local->corp->name]['total'] = 0;
                $corpsTotal[$local->corp->name]['new'] = 0;
                $corpsTotal[$local->corp->name]['same'] = 0;
                $corpsTotal[$local->corp->name]['left'] = 0;
                $corpsTotal[$local->corp->name]['totalInSystem'] = 0;
            }
            if ($local->corp && $local->corp->alliance) {
                $allianceTotal[$local->corp->alliance->name]['details'] = $local->corp->alliance;
                $allianceTotal[$local->corp->alliance->name]['total'] = 0;
                $allianceTotal[$local->corp->alliance->name]['new'] = 0;
                $allianceTotal[$local->corp->alliance->name]['same'] = 0;
                $allianceTotal[$local->corp->alliance->name]['left'] = 0;
                $allianceTotal[$local->corp->alliance->name]['totalInSystem'] = 0;
            }

            if ($local->corp && $local->corp->alliance && $local->corp->alliance->affiliation) {
                $affiliationTotal[$local->corp->alliance->affiliation->short_name]['details'] = $local->corp->alliance->affiliation;
                $affiliationTotal[$local->corp->alliance->affiliation->short_name]['total'] = 0;
                $affiliationTotal[$local->corp->alliance->affiliation->short_name]['new'] = 0;
                $affiliationTotal[$local->corp->alliance->affiliation->short_name]['same'] = 0;
                $affiliationTotal[$local->corp->alliance->affiliation->short_name]['left'] = 0;
                $affiliationTotal[$local->corp->alliance->affiliation->short_name]['totalInSystem'] = 0;
            }
            if (!$local->corp) {
                $corpsTotal['unknown']['details']['standings'] = 0;
                $corpsTotal['unknown']['details']['name'] = 'Unknown';
                $corpsTotal['unknown']['details']['ticker'] = '???';
                $corpsTotal['unknown']['details']['url'] = 'https://images.evetech.net/Corporation/1_64.png';
                $corpsTotal['unknown']['total'] = 0;
                $corpsTotal['unknown']['new'] = 0;
                $corpsTotal['unknown']['same'] = 0;
                $corpsTotal['unknown']['left'] = 0;
                $corpsTotal['unknown']['totalInSystem'] = 0;
            }
        }

        foreach ($allLocals as $local) {
            if ($local->corp) {
                $corpsTotal[$local->corp->name]['total'] = $corpsTotal[$local->corp->name]['total'] + 1;
            }

            if ($local->corp && $local->corp->alliance) {
                $allianceTotal[$local->corp->alliance->name]['total'] = $allianceTotal[$local->corp->alliance->name]['total'] + 1;
            }

            if ($local->corp && $local->corp->alliance && $local->corp->alliance->affiliation) {
                $affiliationTotal[$local->corp->alliance->affiliation->short_name]['total'] = $affiliationTotal[$local->corp->alliance->affiliation->short_name]['total'] + 1;
            }

            if (!$local->corp) {
                $corpsTotal['unknown']['total'] = $corpsTotal['unknown']['total'] + 1;
            }

            if ($local->pivot->new) {
                if ($local->corp) {
                    $corpsTotal[$local->corp->name]['new'] = $corpsTotal[$local->corp->name]['new'] + 1;
                    $corpsTotal[$local->corp->name]['totalInSystem'] = $corpsTotal[$local->corp->name]['totalInSystem'] + 1;
                }
                if ($local->corp && $local->corp->alliance) {
                    $allianceTotal[$local->corp->alliance->name]['new'] = $allianceTotal[$local->corp->alliance->name]['new'] + 1;
                    $allianceTotal[$local->corp->alliance->name]['totalInSystem'] = $allianceTotal[$local->corp->alliance->name]['totalInSystem'] + 1;
                }

                if ($local->corp && $local->corp->alliance && $local->corp->alliance->affiliation) {
                    $affiliationTotal[$local->corp->alliance->affiliation->short_name]['new'] = $affiliationTotal[$local->corp->alliance->affiliation->short_name]['new'] + 1;
                    $affiliationTotal[$local->corp->alliance->affiliation->short_name]['totalInSystem'] = $affiliationTotal[$local->corp->alliance->affiliation->short_name]['totalInSystem'] + 1;
                }

                if (!$local->corp) {
                    $corpsTotal['unknown']['new'] = $corpsTotal['unknown']['new'] + 1;
                    $corpsTotal['unknown']['totalInSystem'] = $corpsTotal['unknown']['totalInSystem'] + 1;
                }
            }

            if ($local->pivot->same) {
                if ($local->corp) {
                    $corpsTotal[$local->corp->name]['same'] = $corpsTotal[$local->corp->name]['same'] + 1;
                    $corpsTotal[$local->corp->name]['totalInSystem'] = $corpsTotal[$local->corp->name]['totalInSystem'] + 1;
                }
                if ($local->corp && $local->corp->alliance) {
                    $allianceTotal[$local->corp->alliance->name]['same'] = $allianceTotal[$local->corp->alliance->name]['same'] + 1;
                    $allianceTotal[$local->corp->alliance->name]['totalInSystem'] = $allianceTotal[$local->corp->alliance->name]['totalInSystem'] + 1;
                }

                if ($local->corp && $local->corp->alliance && $local->corp->alliance->affiliation) {
                    $affiliationTotal[$local->corp->alliance->affiliation->short_name]['same'] = $affiliationTotal[$local->corp->alliance->affiliation->short_name]['same'] + 1;
                    $affiliationTotal[$local->corp->alliance->affiliation->short_name]['totalInSystem'] = $affiliationTotal[$local->corp->alliance->affiliation->short_name]['totalInSystem'] + 1;
                }

                if (!$local->corp) {
                    $corpsTotal['unknown']['same'] = $corpsTotal['unknown']['same'] + 1;
                    $corpsTotal['unknown']['totalInSystem'] = $corpsTotal['unknown']['totalInSystem'] + 1;
                }
            }

            if ($local->pivot->left) {
                if ($local->corp) {
                    $corpsTotal[$local->corp->name]['left'] = $corpsTotal[$local->corp->name]['left'] + 1;
                }
                if ($local->corp && $local->corp->alliance) {
                    $allianceTotal[$local->corp->alliance->name]['left'] = $allianceTotal[$local->corp->alliance->name]['left'] + 1;
                }

                if ($local->corp && $local->corp->alliance && $local->corp->alliance->affiliation) {
                    $affiliationTotal[$local->corp->alliance->affiliation->short_name]['left'] = $affiliationTotal[$local->corp->alliance->affiliation->short_name]['left'] + 1;
                }
                if (!$local->corp) {
                    $corpsTotal['unknown']['left'] = $corpsTotal['unknown']['left'] + 1;
                }
            }
        }

        foreach ($corpsTotal as $key => $corp) {

            $same =   $corpsTotal[$key]['same'];
            $new =   $corpsTotal[$key]['new'];
            $left =   $corpsTotal[$key]['left'];

            if ($new == 0 && $left == 0) {
                $diff = 0;
            } else {

                $diff = $new - $left;
            }
            $corpsTotal[$key]['diff'] = $diff;
        }

        foreach ($allianceTotal as $key => $alliance) {
            $same =   $allianceTotal[$key]['same'];
            $new =   $allianceTotal[$key]['new'];
            $left =   $allianceTotal[$key]['left'];

            if ($new == 0 && $left == 0) {
                $diff = 0;
            } else {

                $diff = $new - $left;
            }
            $allianceTotal[$key]['diff'] = $diff;
        }

        foreach ($affiliationTotal as $key => $affiliation) {
            $same =   $affiliationTotal[$key]['same'];
            $new =   $affiliationTotal[$key]['new'];
            $left =   $affiliationTotal[$key]['left'];

            if ($new == 0 && $left == 0) {
                $diff = 0;
            } else {

                $diff = $new - $left;
            }
            $affiliationTotal[$key]['diff'] = $diff;
        }

        $corpsTotal = collect($corpsTotal)->sortByDesc('totalInSystem')->values()->all();
        $allianceTotal = collect($allianceTotal)->sortByDesc('totalInSystem')->values()->all();
        $affiliationTotal = collect($affiliationTotal)->sortByDesc('totalInSystem')->values()->all();

        return [
            'corps' => $corpsTotal,
            'alliances' => $allianceTotal,
            'affiliations' => $affiliationTotal,
        ];
    }
}


if (!function_exists('workOutItemNumbers')) {
    function workOutItemNumbers($allAllItems, $dscanID)
    {

        $itemTotals = [];
        $groupTotals = [];
        $categoryTotals = [];


        $dscanHistory = DscanHistory::where('dscan_id', $dscanID)
            ->select(
                'itemTotals',
                'groupTotals',
                'categoryTotals'
            )
            ->latest()
            ->first();
        if ($dscanHistory) {
            $oldItemTotals = collect($dscanHistory->itemTotals);
            $oldGroupTotals = collect($dscanHistory->groupTotals);
            $oldCategoryTotals = collect($dscanHistory->categoryTotals);
        } else {
            $oldItemTotals = collect([]);
            $oldGroupTotals = collect([]);
            $oldCategoryTotals = collect([]);
        }


        foreach ($allAllItems as $item) {
            if ($item->item) {
                $itemTotals[$item->item->item_name]['details'] = $item->item;
                $itemTotals[$item->item->item_name]['total'] = 0;
                $itemTotals[$item->item->item_name]['new'] = 0;

                $itemTotals[$item->item->item_name]['same'] = 0;
                $itemTotals[$item->item->item_name]['left'] = 0;
                $itemTotals[$item->item->item_name]['totalOnGrid'] = 0;
                $itemTotals[$item->item->item_name]['totalOffGrid'] = 0;
                $itemTotals[$item->item->item_name]['totalInSystem'] = 0;
            }
            if ($item->item && $item->item->group) {
                $groupTotals[$item->item->group->name]['details'] = $item->item->group;
                $groupTotals[$item->item->group->name]['total'] = 0;
                $groupTotals[$item->item->group->name]['new'] = 0;
                $groupTotals[$item->item->group->name]['same'] = 0;
                $groupTotals[$item->item->group->name]['left'] = 0;
                $groupTotals[$item->item->group->name]['totalOnGrid'] = 0;
                $groupTotals[$item->item->group->name]['totalOffGrid'] = 0;
                $groupTotals[$item->item->group->name]['totalInSystem'] = 0;
            }

            if ($item->item && $item->item->group && $item->item->group->category) {
                $categoryTotals[$item->item->group->category->name]['details'] = $item->item->group->category;
                $categoryTotals[$item->item->group->category->name]['total'] = 0;
                $categoryTotals[$item->item->group->category->name]['new'] = 0;
                $categoryTotals[$item->item->group->category->name]['same'] = 0;
                $categoryTotals[$item->item->group->category->name]['left'] = 0;
                $categoryTotals[$item->item->group->category->name]['totalOnGrid'] = 0;
                $categoryTotals[$item->item->group->category->name]['totalOffGrid'] = 0;
                $categoryTotals[$item->item->group->category->name]['totalInSystem'] = 0;
            }
        }



        foreach ($allAllItems as $item) {

            if ($item->item) {
                $itemTotals[$item->item->item_name]['total'] = $itemTotals[$item->item->item_name]['total'] + 1;
            }

            if ($item->item && $item->item->group) {
                $groupTotals[$item->item->group->name]['total'] = $groupTotals[$item->item->group->name]['total'] + 1;
            }

            if ($item->item && $item->item->group && $item->item->group->category) {
                $categoryTotals[$item->item->group->category->name]['total'] = $categoryTotals[$item->item->group->category->name]['total'] + 1;
            }


            if ($item->new) {
                if ($item->item) {
                    $itemTotals[$item->item->item_name]['new'] = $itemTotals[$item->item->item_name]['new'] + 1;
                    $itemTotals[$item->item->item_name]['totalInSystem'] = $itemTotals[$item->item->item_name]['totalInSystem'] + 1;
                    if ($item->on_grid) {
                        $itemTotals[$item->item->item_name]['totalOnGrid'] = $itemTotals[$item->item->item_name]['totalOnGrid'] + 1;
                    } else {
                        $itemTotals[$item->item->item_name]['totalOffGrid'] = $itemTotals[$item->item->item_name]['totalOffGrid'] + 1;
                    }
                }
                if ($item->item && $item->item->group) {
                    $groupTotals[$item->item->group->name]['new'] = $groupTotals[$item->item->group->name]['new'] + 1;
                    $groupTotals[$item->item->group->name]['totalInSystem'] = $groupTotals[$item->item->group->name]['totalInSystem'] + 1;
                    if ($item->on_grid) {
                        $groupTotals[$item->item->group->name]['totalOnGrid'] = $groupTotals[$item->item->group->name]['totalOnGrid'] + 1;
                    } else {
                        $groupTotals[$item->item->group->name]['totalOffGrid'] = $groupTotals[$item->item->group->name]['totalOffGrid'] + 1;
                    }
                }
                if ($item->item && $item->item->group && $item->item->group->category) {
                    $categoryTotals[$item->item->group->category->name]['new'] = $categoryTotals[$item->item->group->category->name]['new'] + 1;
                    $categoryTotals[$item->item->group->category->name]['totalInSystem'] = $categoryTotals[$item->item->group->category->name]['totalInSystem'] + 1;
                    if ($item->on_grid) {
                        $categoryTotals[$item->item->group->category->name]['totalOnGrid'] = $categoryTotals[$item->item->group->category->name]['totalOnGrid'] + 1;
                    } else {
                        $categoryTotals[$item->item->group->category->name]['totalOffGrid'] = $categoryTotals[$item->item->group->category->name]['totalOffGrid'] + 1;
                    }
                }
            }

            if ($item->same) {
                if ($item->item) {
                    $itemTotals[$item->item->item_name]['same'] = $itemTotals[$item->item->item_name]['same'] + 1;
                    $itemTotals[$item->item->item_name]['totalInSystem'] = $itemTotals[$item->item->item_name]['totalInSystem'] + 1;
                    if ($item->on_grid) {
                        $itemTotals[$item->item->item_name]['totalOnGrid'] = $itemTotals[$item->item->item_name]['totalOnGrid'] + 1;
                    } else {
                        $itemTotals[$item->item->item_name]['totalOffGrid'] = $itemTotals[$item->item->item_name]['totalOffGrid'] + 1;
                    }
                }
                if ($item->item && $item->item->group) {
                    $groupTotals[$item->item->group->name]['same'] = $groupTotals[$item->item->group->name]['same'] + 1;
                    $groupTotals[$item->item->group->name]['totalInSystem'] = $groupTotals[$item->item->group->name]['totalInSystem'] + 1;
                    if ($item->on_grid) {
                        $groupTotals[$item->item->group->name]['totalOnGrid'] = $groupTotals[$item->item->group->name]['totalOnGrid'] + 1;
                    } else {
                        $groupTotals[$item->item->group->name]['totalOffGrid'] = $groupTotals[$item->item->group->name]['totalOffGrid'] + 1;
                    }
                }
                if ($item->item && $item->item->group && $item->item->group->category) {
                    $categoryTotals[$item->item->group->category->name]['same'] = $categoryTotals[$item->item->group->category->name]['same'] + 1;
                    $categoryTotals[$item->item->group->category->name]['totalInSystem'] = $categoryTotals[$item->item->group->category->name]['totalInSystem'] + 1;
                    if ($item->on_grid) {
                        $categoryTotals[$item->item->group->category->name]['totalOnGrid'] = $categoryTotals[$item->item->group->category->name]['totalOnGrid'] + 1;
                    } else {
                        $categoryTotals[$item->item->group->category->name]['totalOffGrid'] = $categoryTotals[$item->item->group->category->name]['totalOffGrid'] + 1;
                    }
                }
            }

            if ($item->left) {
                if ($item->item) {
                    $itemTotals[$item->item->item_name]['left'] = $itemTotals[$item->item->item_name]['left'] + 1;
                }
                if ($item->item && $item->item->group) {
                    $groupTotals[$item->item->group->name]['left'] = $groupTotals[$item->item->group->name]['left'] + 1;
                }
                if ($item->item && $item->item->group && $item->item->group->category) {
                    $categoryTotals[$item->item->group->category->name]['left'] = $categoryTotals[$item->item->group->category->name]['left'] + 1;
                }
            }
        }





        foreach ($itemTotals as $key => $item) {
            $same =   $itemTotals[$key]['same'];
            $new =   $itemTotals[$key]['new'];
            $left =   $itemTotals[$key]['left'];

            if ($new == 0 && $left == 0) {
                $diff = 0;
            } else {

                $diff = $new - $left;
            }
            $itemTotals[$key]['diff'] = $diff;

            $oldItem = $oldItemTotals->where('details.id', $itemTotals[$key]['details']['id'])->first();

            $itemTotals[$key]['oldTotalOffGrid'] = $oldItem['totalOffGrid'] ?? 0;
            $itemTotals[$key]['oldTotalOnGrid'] = $oldItem['totalOnGrid'] ?? 0;
        }

        foreach ($groupTotals as $key => $group) {
            $same =   $groupTotals[$key]['same'];
            $new =   $groupTotals[$key]['new'];
            $left =   $groupTotals[$key]['left'];

            if ($new == 0 && $left == 0) {
                $diff = 0;
            } else {

                $diff = $new - $left;
            }
            $groupTotals[$key]['diff'] = $diff;


            $oldGroup = $oldGroupTotals->where('details.id', $groupTotals[$key]['details']['id'])->first();

            $groupTotals[$key]['oldTotalOffGrid'] = $oldGroup['totalOffGrid'] ?? 0;
            $groupTotals[$key]['oldTotalOnGrid'] = $oldGroup['totalOnGrid'] ?? 0;
        }

        foreach ($categoryTotals as $key => $category) {
            $same =   $categoryTotals[$key]['same'];
            $new =   $categoryTotals[$key]['new'];
            $left =   $categoryTotals[$key]['left'];

            if ($new == 0 && $left == 0) {
                $diff = 0;
            } else {

                $diff = $new - $left;
            }
            $categoryTotals[$key]['diff'] = $diff;


            $oldCategory = $oldCategoryTotals->where('details.id', $categoryTotals[$key]['details']['id'])->first();

            $categoryTotals[$key]['oldTotalOffGrid'] = $oldCategory['totalOffGrid'] ?? 0;
            $categoryTotals[$key]['oldTotalOnGrid'] = $oldCategory['totalOnGrid'] ?? 0;

            // dd($categoryTotals[$key]);
        }



        $itemTotals = collect($itemTotals)->sortByDesc('totalInSystem')->values()->all();
        $groupTotals = collect($groupTotals)->sortByDesc('totalInSystem')->values()->all();
        $categoryTotals = collect($categoryTotals)->sortByDesc('totalInSystem')->values()->all();


        return [
            'items' => $itemTotals,
            'groups' => $groupTotals,
            'category' => $categoryTotals,
        ];
    }
}

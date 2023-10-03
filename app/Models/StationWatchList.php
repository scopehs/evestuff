<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class StationWatchList extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = [
        'settings'
    ];

    protected $casts = [
        'settings' => 'array'
    ];

    protected $attributes = [
        'settings' => '{"station_id": [], "system_id": [], "constellation_id": [], "region_id": [], "role_id": [], "alliance_id": [], "type_id": [], "user_id": []}'
    ];



    public function stations()
    {
        return $this->belongsToMany(Station::class, 'station_station_watch_lists', 'station_watch_list_id', 'station_id');
    }

    public function systems()
    {
        return $this->belongsToMany(System::class, 'system_station_watch_lists', 'station_watch_list_id', 'system_id');
    }

    public function constellations()
    {
        return $this->belongsToMany(Constellation::class, 'constellation_station_watch_lists', 'station_watch_list_id', 'constellation_id');
    }

    public function regions()
    {
        return $this->belongsToMany(Region::class, 'region_station_watch_lists', 'station_watch_list_id', 'region_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_station_watch_lists', 'station_watch_list_id', 'role_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_station_watch_lists', 'station_watch_list_id', 'user_id');
    }

    public function alliances()
    {
        return $this->belongsToMany(Alliance::class, 'alliance_station_watch_lists', 'station_watch_list_id', 'alliance_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_station_watch_lists', 'station_watch_list_id', 'item_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use HasPermissions;
    use HasApiTokens;
    use LaravelPermissionToVueJS;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pri_grp' => 'integer',
    ];

    public function campaignsystemusers()
    {
        return $this->hasMany(CampaignSystemUsers::class);
    }

    public function stationNotification()
    {
        return $this->hasMany(StationNotification::class);
    }

    public function feedback()
    {
        return $this->hasMany(FeedBack::class);
    }

    public function logs()
    {
        return $this->hasMany(Logging::class);
    }

    public function reconTasksEdit()
    {
        return $this->hasMany(ReconTasks::class, 'made_by_user_id');
    }

    public function reconTasksMade()
    {
        return $this->hasMany(ReconTasks::class, 'edited_by_user_id');
    }

    public function reconTaskSystem()
    {
        return $this->hasMany(ReconTasks::class);
    }

    public function keys()
    {
        return $this->belongsToMany(KeyType::class, 'user_key_joins');
    }

    public function opUsers()
    {
        return $this->hasMany(OperationUser::class);
    }


    public function operationInfoUser()
    {
        return $this->hasMany(OperationInfoUserList::class);
    }

    public function getAllPermissionsAttribute()
    {
        $permissions = [];
        foreach (Permission::all() as $permission) {

            //   dd($name);
            if (Auth::user()->can($permission->name)) {
                $permissions[] = $permission->name;
            }
        }

        return $permissions;
    }

    public function getAllRolesAttribute()
    {
        $roles = [];
        foreach (Role::all() as $role) {

            //   dd($name);
            if (Auth::user()->can($role->name)) {
                $roles[] = $role->name;
            }
        }

        return $roles;
    }

    public function getCheckPermissions()
    {
        return checkPermissions();
    }
}

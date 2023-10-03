<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class RemoveAllRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all Roles off all people';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->syncRoles([]);
        }

        $role = Role::findByName('Chilled');
        $role->delete();
        $role = Role::findByName('GSFOE FC');
        $role->delete();
        $role = Role::findByName('Gunner');
        $role->delete();
        $role = Role::findByName('Key Master');
        $role->delete();
        $role = Role::findByName('Mega Sheet');
        $role->delete();
        $role = Role::findByName('Nats');
        $role->delete();
        $role = Role::findByName('Super Chilled');
        $role->delete();
        $role = Role::findByName('Super Violence');
        $role->delete();
        $role = Role::findByName('Top Chill');
        $role->delete();
        $role = Role::findByName('Violence');
        $role->delete();
        $role = Role::findByName('Welp');
        $role->delete();
        $role = Role::findByName('Wizard');
        $role->delete();
        $role = Role::findByName('Recon Leader');
        $role->delete();
    }
}

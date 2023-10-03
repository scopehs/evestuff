<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOrReplaceUserRolesRecordsViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'view:CreateOrReplaceUserRolesRecordsView';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will make the view table for User Roles';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::statement("CREATE OR REPLACE VIEW user_roles_records AS SELECT model_has_roles.model_id AS 'user_id',
        users.name AS 'user_name',
        model_has_roles.role_id AS 'role_id',
        roles.name AS 'role_name',
        concat(model_has_roles.model_id,model_has_roles.role_id) AS 'id'
        FROM model_has_roles
        JOIN users ON users.id = model_has_roles.model_id
        JOIN roles ON roles.id = model_has_roles.role_id");
    }
}

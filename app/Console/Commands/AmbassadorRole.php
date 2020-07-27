<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AmbassadorRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ambassador';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $role=Role::firstOrCreate(['name'=>'ambassador']);
        $permission=Permission::firstOrCreate(['name'=>'ambassador-view']);
        $role->givePermissionTo($permission);
        $role->givePermissionTo('enable-login');
        $this->line('Created role and permissions');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mint\Service\Repositories\InstallRepository;

class Install extends Command
{
    protected $repo;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install {--db=} {--dbuser=root} {--dbpass=}';

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
    public function __construct(
        InstallRepository $repo
    ) {
        $this->repo = $repo;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $_SERVER['SERVER_NAME'] = 'localhost';
        $this->repo->install([
            'access_code'    => '',
            'envato_email'   => '',
            'db_host'        => 'localhost',
            'db_database'    => $this->option('db'),
            'db_port'        => '3306',
            'db_username'    => $this->option('dbuser'),
            'db_password'    => $this->option('dbpass'),
            'email'          => 'admin@example.comm',
            'username'       => 'admin',
            'first_name'     => 'admin',
            'last_name'      => 'admin',
            'password'       => 'secret',
            'contact_number' => '123456789',
        ]);
        $this->info('Installed.');
    }
}

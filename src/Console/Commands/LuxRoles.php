<?php

namespace Pieldefoca\Lux\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class LuxRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lux:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the roles and permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Role::create(['name' => 'super admin']);
        $role = Role::create(['name' => 'admin']);
    }
}

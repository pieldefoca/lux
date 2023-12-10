<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Pieldefoca\Lux\Models\Locale;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'admin']);
    }
}

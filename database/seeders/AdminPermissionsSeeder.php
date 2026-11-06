<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\AdminPermission;

class AdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Deprecated seeder - admin_permissions table is no longer required
        $this->command->info('Admin permissions seeder is deprecated and skipped.');
    }
}

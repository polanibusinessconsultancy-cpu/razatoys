<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin role creation bypassed (deprecated in Phase 12D)

        // Create Admin role if it doesn't exist
        if (!Role::where('name', 'Admin')->exists()) {
            Role::create(['name' => 'Admin']);
            $this->command->info('Admin role created successfully!');
        }

        // Create User role if it doesn't exist
        if (!Role::where('name', 'User')->exists()) {
            Role::create(['name' => 'User']);
            $this->command->info('User role created successfully!');
        }
    }
}

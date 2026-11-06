<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@polani.com'],
            [
                'name' => 'Polani Admin',
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'password' => Hash::make('PolaniAdmin@123'),
                'phone' => '1234567890',
                'location' => 'System',
                'about' => 'Single admin account for product management',
            ]
        );
    
        // Ensure Admin role exists
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
    
        // Attach role if not already attached
        if (!$user->roles->contains($adminRole->id)) {
            $user->roles()->attach($adminRole->id);
        }
    
        $this->command->info('Admin user created/updated and role assigned.');
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new One Stop Mart administrator account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Creating a new One Stop Mart Administrator Account...");

        $name = $this->ask('Owner Name');

        // Email validation loop
        $email = '';
        while (true) {
            $email = $this->ask('Owner Email');

            $validator = Validator::make(['email' => $email], [
                'email' => 'required|email|unique:users,email'
            ]);

            if ($validator->fails()) {
                $this->error($validator->errors()->first('email'));
                continue;
            }
            break;
        }

        // Password confirmation loop
        $password = '';
        while (true) {
            $password = $this->secret('Password');
            $password_confirmation = $this->secret('Confirm Password');

            if (empty($password)) {
                $this->error('Password cannot be empty.');
                continue;
            }

            if ($password !== $password_confirmation) {
                $this->error('Passwords do not match. Please try again.');
                continue;
            }
            break;
        }

        // Check if Admin role exists, create if not
        $adminRole = Role::where('name', 'Admin')->first();
        if (!$adminRole) {
            $adminRole = Role::create(['name' => 'Admin']);
            $this->info("Created Admin role in database.");
        }

        // Create the user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        // Attach the role
        $user->roles()->attach($adminRole->id);

        $this->info("Administrator user '{$user->name}' ({$user->email}) created successfully!");

        return 0;
    }
}

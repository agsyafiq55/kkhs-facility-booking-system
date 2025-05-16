<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin-user {name?} {email?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user for the booking system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name') ?? $this->ask('Enter admin name');
        $email = $this->argument('email') ?? $this->ask('Enter admin email');
        $password = $this->argument('password') ?? $this->secret('Enter admin password');

        // Check if user with email already exists
        $existingUser = User::where('email', $email)->first();
        
        if ($existingUser) {
            // Update the existing user to admin role
            $existingUser->update([
                'role' => 'admin',
            ]);
            
            $this->info("User {$email} has been updated to admin role.");
            return;
        }
        
        // Create a new admin user
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        
        $this->info("Admin user {$email} has been created successfully.");
    }
}

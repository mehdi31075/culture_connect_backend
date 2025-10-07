<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin credentials from environment variables
        $adminEmail = env('ADMIN_EMAIL', 'admin@cultureconnect.com');
        $adminPassword = env('ADMIN_PASSWORD', 'admin123');
        $adminName = env('ADMIN_NAME', 'Admin User');
        $adminPhone = env('ADMIN_PHONE', '+1234567890');

        // Check if admin user already exists
        $existingAdmin = User::where('email', $adminEmail)->orWhere('phone', $adminPhone)->first();

        if ($existingAdmin) {
            // Update existing admin user
            $existingAdmin->update([
                'name' => $adminName,
                'email' => $adminEmail,
                'phone' => $adminPhone,
                'password' => Hash::make($adminPassword),
                'is_staff' => true,
                'is_active' => true,
                'locale' => 'en',
            ]);

            $this->command->info("Admin user updated: {$adminEmail}");
        } else {
            // Create new admin user
            User::create([
                'name' => $adminName,
                'email' => $adminEmail,
                'phone' => $adminPhone,
                'password' => Hash::make($adminPassword),
                'is_staff' => true,
                'is_active' => true,
                'locale' => 'en',
            ]);

            $this->command->info("Admin user created: {$adminEmail}");
        }
    }
}

<?php
// database/seeders/AdminSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default super admin
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@zota.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create additional admin if needed
        Admin::create([
            'name' => 'Admin User',
            'email' => 'admin2@zota.com', 
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->command->info('Default admins created:');
        $this->command->info('1. Super Admin - Email: admin@zota.com | Password: password123');
        $this->command->info('2. Admin User - Email: admin2@zota.com | Password: password123');
    }
}
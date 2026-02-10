<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // User::factory(10)->create();
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'permission' => 'all',
            'status' => 1,
            'deleted' => 0,
            'latest_edit_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Client::factory()->create();

        DB::table('modules')->insert(
            [
                'name' => 'userManagement',
                'table' => 'users',
                'primary_id' => 'id',
                'unique_field' => 'email',
                'status' => 1,
                'deleted' => 0,
                'latest_edit_by' => 1,
                'added_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

  
    }
}

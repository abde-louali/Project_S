<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Classe;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Admin::factory()->create([
            'first_name' => 'Aziza',
            'password' => Hash::make('Aziza123'),
            'last_name' => 'admin',
            'cin' => 'AZ123',
            'username' => 'aziza'
        ]);

        Classe::factory(4)->create();
    }
}

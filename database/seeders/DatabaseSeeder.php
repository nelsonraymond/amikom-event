<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
public function run(): void
    {
    // 1. Akun Admin Utama
    \App\Models\User::create([
    'name' => 'Admin Amikom',
    'email' => 'admin@amikom.ac.id',
    'password' => bcrypt('password'),
    'role' => 'admin',
    ]);

    // 2. Insert Kategori Event
    $category = \App\Models\Category::create([
    'name' => 'Seminar IT',
    'slug' => 'seminar-it',
    ]);

    $category2 = \App\Models\Category::firstOrCreate([
        'name' => 'Entertaiment',
        'slug' => 'entertaiment',
    ]);
    
    $category3 = \App\Models\Category::firstOrCreate([
    'name' => 'Workshop',
    'slug' => 'workshop',
        ]);

    $category4 = \App\Models\Category::firstOrCreate([
    'name' => 'Content Creator',
    'slug' => 'content-creator',
    ]);
        

    }
}
<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'name' => 'admin',
            'email' => 'admin@ex.com',
            'password' => bcrypt('admin'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'customer',
            'email' => 'customer@ex.com',
            'password' => bcrypt('customer'),
            'role' => 'customer'
        ]);

        Kategori::create([
            'name' => 'VGA'
        ]);

        Kategori::create([
            'name' => 'Motherboad'
        ]);

        Produk::create([
            'kategori_id' => 1,
            'name' => 'VGA RTX 4090',
            'harga' => '250000',
            'foto' => 'img/rtx4090.jpg'
        ]);

        Produk::create([
            'kategori_id' => 2,
            'name' => 'Motherboard Z790',
            'harga' => '200000',
            'foto' => 'img/z790.jpg'
        ]);
    }
}

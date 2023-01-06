<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::upsert([
            [
                'id' => 1,
                'name' => 'Merchant',
                'email' => 'alothmanasala@gmail.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'type' => 0
            ],

        ], ['name', 'email', 'password', 'type']);
        $this->call(IngredientSeeder::class);
        $this->call(ProductSeeder::class);
    }
}

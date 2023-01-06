<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ingredient::upsert([
           $a = [
                'name' => 'Beef',
                'slug' => Str::slug('Beef'),
                'stock_level' => 20000,
                'in_stock' => 20000,
                'default_amount' => 150
            ], [
                'name' => 'Cheese',
                'slug' => Str::slug('Cheese'),
                'stock_level' => 5000,
                'in_stock' => 5000,
                'default_amount' => 30

            ], [
                'name' => 'Onion',
                'slug' => Str::slug('Onion'),
                'stock_level' => 1000,
                'in_stock' => 1000,
                'default_amount' => 20

            ]
        ], [
            'name', 'slug', 'stock_level', 'in_stock',
        ]);


    }
}

<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $burger = Product::updateOrCreate([
            'name' => 'Burger',
            'slug' => Str::slug('Burger')
        ]);



        // Insert data in product ingredients table
        $ingredients = Ingredient::select('id', 'slug')->get();

        DB::table('product_ingredients')->upsert([
            [
                'product_id' => $burger->id,
                'ingredient_id' => $ingredients->where('slug', 'beef')->first()->id
            ],
            ['product_id' => $burger->id, 'ingredient_id' => $ingredients->where('slug', 'onion')->first()->id],
            ['product_id' => $burger->id, 'ingredient_id' => $ingredients->where('slug', 'cheese')->first()->id]
        ], ['product_id', 'ingredient_id']);


    }
}

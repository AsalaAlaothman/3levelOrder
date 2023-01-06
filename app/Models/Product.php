<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'products';
    protected $guarded = [];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'product_ingredients')
            ->whereRaw('ingredients.in_stock >= ingredients.default_amount')
            ->select(
                "ingredients.id",
                "name",
                "slug",
                "stock_level",
                "in_stock",
                "consumed_stock",
                "default_amount"
            );
    }
}

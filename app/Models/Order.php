<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'orders';
    protected $guarded = [];

    public function order_product_ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'order_product_ingredients');
    }
    public function products(){
        return $this->belongsToMany(Product::class , 'order_products');

    }

}

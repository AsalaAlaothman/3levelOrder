<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ingredients';
    protected $guarded = [];

    public function child()
    {

       return $this->hasMany(IngredientLevelEmails::class)->select('ingredient_id', 'user_id', 'supplier_name');
    }
}

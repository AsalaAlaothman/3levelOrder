<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IngredientLevelEmails extends Model
{
    use HasFactory,SoftDeletes;
    protected $table ='ingredient_level_emails';
    protected $guarded =[];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ingredient;
use App\Models\RecipeStep;
use App\Models\Category;

class Recipe extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'cooking_time',
        'difficulty',
        'servings',
        'image_url',
        'calories',
        'category_id',
        'user_id',
        'status',
        'rejection_reason',
        'is_approved',
        'proteins',
        'fats',
        'carbs'
    ];
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    public function steps()
    {
        return $this->hasMany(RecipeStep::class);
    }
    public function views()
    {
        return $this->hasMany(RecipeView::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

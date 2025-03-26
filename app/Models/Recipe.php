<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'instructions',
        'cooking_time',
        'serving_size',
        'category_id',
        'attachment'
    ];

    /**
     * Get the image path from attachment.
     */
    public function getImagePathAttribute()
    {
        return $this->attachment;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipes_ingredients')
            ->withPivot('amount', 'unit')
            ->withTimestamps();
    }
}

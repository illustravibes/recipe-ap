<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\ELoquent\Relations\BelongsTo;
use Illuminate\Database\ELoquent\Relations\BelongsToMany;

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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'recipes_ingredients')
            ->withPivot('amount', 'unit')
            ->withTimestamps();
    }
}

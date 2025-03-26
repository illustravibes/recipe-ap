<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;

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
        'attachment',
        'secret_instructions',
        'has_secret',
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

    // Encrypt secret instructions when setting
    public function setSecretInstructionsAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['secret_instructions'] = Crypt::encryptString($value);
            $this->attributes['has_secret'] = true;
        } else {
            $this->attributes['secret_instructions'] = null;
            $this->attributes['has_secret'] = false;
        }
    }

    // Decrypt secret instructions when getting
    public function getSecretInstructionsAttribute($value)
    {
        if (!empty($value)) {
            return Crypt::decryptString($value);
        }

        return null;
    }
}

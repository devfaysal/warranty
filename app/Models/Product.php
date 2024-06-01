<?php

namespace App\Models;

use App\Enums\Brand;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'brand' => Brand::class,
        ];
    }

    public function meta(): HasMany
    {
        return $this->hasMany(ProductMeta::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }
}

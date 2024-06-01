<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unit extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
        ];
    }

    public function Product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function rechargeGroup(): BelongsTo
    {
        return $this->belongsTo(RechargeGroup::class);
    }
}

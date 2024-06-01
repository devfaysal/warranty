<?php

namespace App\Models;

use App\Enums\ConnectionType;
use App\Enums\MobileOperator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'mobile_operator' => MobileOperator::class,
            'connection_type' => ConnectionType::class,
        ];
    }
}

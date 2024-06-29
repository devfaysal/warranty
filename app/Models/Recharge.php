<?php

namespace App\Models;

use App\Enums\ConnectionType;
use App\Enums\MobileOperator;
use App\Enums\RechargeStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recharge extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => RechargeStatus::class,
            'mobile_operator' => MobileOperator::class,
            'connection_type' => ConnectionType::class,
        ];
    }
}

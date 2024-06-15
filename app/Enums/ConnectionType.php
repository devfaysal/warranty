<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ConnectionType: string implements HasLabel
{
    case Prepaid = 'prepaid';
    case Postpaid = 'postpaid';

    public function getLabel(): ?string
    {
        return match ($this) {
            ConnectionType::Prepaid => 'প্রিপেইড',
            ConnectionType::Postpaid => 'পোস্টপেইড',
        };
    }
}

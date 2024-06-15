<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MobileOperator: string implements HasLabel
{
    case Grameenphone = 'GP';
    case Banglalink = 'BL';
    case Airtel = 'AT';
    case Robi = 'RB';
    case Teletalk = 'TT';
    case Skitto = 'ST';

    public function getLabel(): ?string
    {
        return match ($this) {
            MobileOperator::Grameenphone => 'গ্রামীনফোন',
            MobileOperator::Banglalink => 'বাংলালিংক',
            MobileOperator::Airtel => 'এয়ারটেল',
            MobileOperator::Robi => 'রবি',
            MobileOperator::Teletalk => 'টেলিটক',
            MobileOperator::Skitto => 'স্কিটো ',
        };
    }
}

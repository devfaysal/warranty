<?php

namespace App\Enums;

enum MobileOperator: string
{
    case Grameenphone = 'GP';
    case Banglalink = 'BL';
    case Airtel = 'AT';
    case Robi = 'RB';
    case Teletalk = 'TT';
    case Skitto = 'ST';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}

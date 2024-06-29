<?php

namespace App\Enums;

enum RechargeStatus: string
{
    case Submitted = 'Submitted';
    case Failed = 'Failed';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}

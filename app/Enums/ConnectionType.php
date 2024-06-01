<?php

namespace App\Enums;

enum ConnectionType: string
{
    case Prepaid = 'prepaid';
    case Postpaid = 'postpaid';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}

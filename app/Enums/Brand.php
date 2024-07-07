<?php

namespace App\Enums;

enum Brand: string
{
    case Benq = 'Benq';
    case Dahua = 'Dahua';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}

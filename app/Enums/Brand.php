<?php

namespace App\Enums;

enum Brand: string
{
    case Benq = 'Benq';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}

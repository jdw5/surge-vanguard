<?php

namespace Workbench\App\Enums;

enum Status: int
{
    case AVAILABLE = 0;
    case UNAVAILABLE = 1;
    case COMING_SOON = 2;

    public function label(): string
    {
        return match ($this) {
            self::AVAILABLE => 'Available',
            self::UNAVAILABLE => 'Unavailable',
            self::COMING_SOON => 'Coming soon',
        };
    }
}
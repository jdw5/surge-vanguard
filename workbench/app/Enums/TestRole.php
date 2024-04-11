<?php

namespace Workbench\App\Enums;

enum TestRole: int
{
    case USER = 0;
    case ADMIN = 1;
    case SUPER_ADMIN = 2;

    public function label(): string
    {
        return match ($this) {
            self::USER => 'User',
            self::ADMIN => 'Admin',
            self::SUPER_ADMIN => 'Super Admin',
        };
    }
}
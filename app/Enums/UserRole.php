<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case INSPECTOR = 'inspector';
    case SUPERVISOR = 'supervisor';
    case MAINTENANCE = 'maintenance';

    /**
     * Get the human-readable label for this role.
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::INSPECTOR => 'Inspector',
            self::SUPERVISOR => 'Supervisor',
            self::MAINTENANCE => 'Maintenance Team',
        };
    }
}

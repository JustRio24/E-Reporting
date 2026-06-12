<?php

namespace App\Enums;

enum DamageSeverity: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case CRITICAL = 'critical';

    /**
     * Get the human-readable label for this severity.
     */
    public function label(): string
    {
        return match ($this) {
            self::LOW => 'Low',
            self::MEDIUM => 'Medium',
            self::HIGH => 'High',
            self::CRITICAL => 'Critical',
        };
    }

    /**
     * Get the color associated with this severity for UI display.
     */
    public function color(): string
    {
        return match ($this) {
            self::LOW => '#059669',
            self::MEDIUM => '#D97706',
            self::HIGH => '#f26522',
            self::CRITICAL => '#DC2626',
        };
    }
}

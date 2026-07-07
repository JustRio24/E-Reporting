<?php

namespace App\Enums;

enum DamageStatus: string
{
    case DRAFT = 'draft';
    case REPORTED = 'reported';
    case VERIFIED = 'verified';
    case ASSIGNED = 'assigned';
    case IN_PROGRESS = 'in_progress';
    case WAITING_VERIFICATION = 'waiting_verification';
    case COMPLETED = 'completed';

    /**
     * Get the human-readable label for this status.
     */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::REPORTED => 'Reported',
            self::VERIFIED => 'Verified',
            self::ASSIGNED => 'Assigned',
            self::IN_PROGRESS => 'In Progress',
            self::WAITING_VERIFICATION => 'Waiting Verification',
            self::COMPLETED => 'Completed',
        };
    }

    /**
     * Get the map pin color for GIS display.
     */
    public function mapColor(): string
    {
        return match ($this) {
            self::COMPLETED => 'green',
            self::IN_PROGRESS, self::WAITING_VERIFICATION, self::ASSIGNED => 'yellow',
            default => 'red',
        };
    }

    /**
     * Check if this status is considered "active" (not completed).
     */
    public function isActive(): bool
    {
        return $this !== self::COMPLETED;
    }

    /**
     * Get the allowed next statuses from the current status.
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::DRAFT => [self::REPORTED],
            self::REPORTED => [self::VERIFIED, self::DRAFT],
            self::VERIFIED => [self::ASSIGNED, self::DRAFT],
            self::ASSIGNED => [self::IN_PROGRESS],
            self::IN_PROGRESS => [self::WAITING_VERIFICATION],
            self::WAITING_VERIFICATION => [self::COMPLETED, self::IN_PROGRESS],
            self::COMPLETED => [],
        };
    }
}

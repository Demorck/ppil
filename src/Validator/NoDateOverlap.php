<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class NoDateOverlap extends Constraint
{
    public string $message = "Le véhicule est déjà réservé pour cette période.";

    public array $existingRanges = [];

    public function getRequiredOptions(): array
    {
        return ['existingRanges'];
    }

    public function getDefaultOption(): string
    {
        return 'existingRanges';
    }

    public function validatedBy(): string
    {
        return static::class.'Validator';
    }
}
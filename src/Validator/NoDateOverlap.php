<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class NoDateOverlap extends Constraint
{
    public string $message = "La plage de date se chevauche avec une autre";

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
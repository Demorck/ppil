<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NoDateOverlapValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof NoDateOverlap) {
            return;
        }

        $form = $this->context->getRoot();
        $dateDebut = $form->get('dateDebut')->getData();
        $dateFin = $form->get('dateFin')->getData();
        $existingRanges = $constraint->existingRanges ?? [];

        if (!$dateDebut || !$dateFin) {
            return;
        }

        foreach ($existingRanges as $range) {
            $existingStart = $range['dateDebut'];
            $existingEnd = $range['dateFin'];

            // Vérifier la condition de chevauchement
            if ($dateDebut < $existingEnd && $dateFin > $existingStart) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
                return;
            }
        }
    }
}

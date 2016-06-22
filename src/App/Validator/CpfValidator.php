<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CpfValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        if (! $this->cpf_valido($value) ) {
            $this->context->buildViolation($constraint->message)
                    ->setParameter('%string%', $value)
                    ->addViolation();
        }
    }

    private function cpf_valido($cpf)
    {
        $cpf = str_replace(array('.', '-'), '', $cpf);
        $cpf = trim($cpf);

        $soma = 0;

        if (strlen($cpf) <> 11)
            return false;

        if (preg_match('/(\d)\1{10}/', $cpf) ){
            return false;
        }
        
        for ($i = 0; $i < 9; $i++) {
            $soma += (($i + 1) * $cpf[$i]);
        }

        $d1 = ($soma % 11);

        if ($d1 == 10) {
            $d1 = 0;
        }

        $soma = 0;

        for ($i = 9, $j = 0; $i > 0; $i--, $j++) {
            $soma += ($i * $cpf[$j]);
        }

        $d2 = ($soma % 11);

        if ($d2 == 10) {
            $d2 = 0;
        }

        if ($d1 == $cpf[9] && $d2 == $cpf[10]) {
            return true;
        } else {
            return false;
        }
    }

}

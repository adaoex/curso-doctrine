<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class Cpf extends Constraint
{
    public $message = 'Este número de CPF é inválido.';
}

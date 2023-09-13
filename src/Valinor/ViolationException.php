<?php

declare(strict_types=1);

namespace App\Valinor;

use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationException extends RuntimeException
{
    public function __construct(private readonly ConstraintViolationListInterface $constraintViolationList)
    {
        parent::__construct('validation errors');
    }

    public function getConstraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }
}

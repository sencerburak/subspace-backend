<?php

declare(strict_types=1);

namespace App\Domain\DomainException;

use Exception;

abstract class DomainException extends Exception
{
}

class DatabaseOperationException extends DomainException
{
    public function __construct(string $message = 'A database error occurred', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

class ValidationException extends DomainException
{
    private array $errors;

    public function __construct(array $errors, string $message = 'Validation failed', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
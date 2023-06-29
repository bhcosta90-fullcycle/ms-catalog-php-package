<?php

declare(strict_types=1);

namespace BRCas\CA\Domain\Validation;

use BRCas\CA\Domain\Exceptions\EntityValidationException;

class DomainValidation
{
    protected function __construct(protected $value)
    {
        //
    }

    public static function make($value)
    {
        return new static($value);
    }

    public function notNull(?string $exceptionMessage = null)
    {
        if ($this->value === null || trim($this->value) === '') {
            throw new EntityValidationException($exceptionMessage ?: "This value is null");
        }

        return $this;
    }

    public function strMaxLength(int $total = 255, ?string $exceptionMessage = null)
    {
        if (strlen($this->value) > $total) {
            throw new EntityValidationException($exceptionMessage ?: "This value must not be greater than {$total} characters");
        }

        return $this;
    }

    public function strMinLength(int $total = 3, ?string $exceptionMessage = null)
    {
        if (strlen($this->value) < $total) {
            throw new EntityValidationException($exceptionMessage ?: "This value must at least {$total} characters");
        }

        return $this;
    }

    public function strCanNullMaxLength(int $total = 255, ?string $exceptionMessage = null)
    {
        if (!empty($this->value) && strlen($this->value) > $total) {
            throw new EntityValidationException($exceptionMessage ?: "This value must not be greater than {$total} characters");
        }
    }

    public function strCanNullMinLength(int $total = 3, ?string $exceptionMessage = null)
    {
        if (!empty($this->value) && strlen($this->value) < $total) {
            throw new EntityValidationException($exceptionMessage ?: "This value must at least {$total} characters");
        }

        return $this;
    }
}

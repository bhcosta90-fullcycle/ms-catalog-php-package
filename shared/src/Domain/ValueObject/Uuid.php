<?php

declare(strict_types=1);

namespace BRCas\CA\Domain\ValueObject;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid as UuidUuid;

class Uuid
{
    public function __construct(protected string $value)
    {
        $this->validate();
    }

    protected function validate()
    {
        if (!UuidUuid::isValid($this->value)) {
            throw new InvalidArgumentException(sprintf('<%s> does not allow the value <%s>', static::class, $this->value));
        }
    }

    public static function make(): self
    {
        return new self(UuidUuid::uuid7()->toString());
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

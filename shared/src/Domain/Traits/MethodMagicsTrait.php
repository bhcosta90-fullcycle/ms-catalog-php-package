<?php

declare(strict_types=1);

namespace BRCas\CA\Domain\Traits;

use Exception;

trait MethodMagicsTrait
{
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        $className = get_class($this);
        throw new Exception("Property {$property} not found in class {$className}");
    }

    public function id(): string
    {
        return (string) $this->id;
    }

    public function createdAt($format = 'Y-m-d H:i:s'): string
    {
        return (string) $this->createdAt->format($format);
    }
}

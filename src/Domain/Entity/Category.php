<?php

declare(strict_types=1);

namespace BRCas\MV\Domain\Entity;

use BRCas\CA\Domain\Traits\MethodMagicsTrait;
use BRCas\CA\Domain\Validation\DomainValidation;
use BRCas\CA\Domain\ValueObject\Uuid;
use DateTime;

class Category
{
    use MethodMagicsTrait;

    public function __construct(
        protected string $name,
        protected ?string $description = null,
        protected bool $isActive = true,
        protected Uuid|string $id = '',
        protected DateTime|string $createdAt = '',
    ) {
        $this->id = $this->id
            ? new Uuid($this->id)
            : Uuid::make();

        $this->createdAt = $this->createdAt
            ? new DateTime($this->createdAt)
            : new DateTime();

        $this->validate();
    }

    public function enable(): void
    {
        $this->isActive = true;
    }

    public function disable(): void
    {
        $this->isActive = false;
    }

    public function update(string $name, ?string $description)
    {
        $this->name = $name;
        $this->description = $description;

        $this->validate();
    }

    protected function validate(): void
    {
        DomainValidation::make($this->name)
            ->strMinLength()
            ->strMaxLength();

        DomainValidation::make($this->description)
            ->strCanNullMinLength()
            ->strCanNullMaxLength();
    }
}

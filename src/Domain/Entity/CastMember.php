<?php

declare(strict_types=1);

namespace BRCas\MV\Domain\Entity;

use BRCas\CA\Domain\Traits\MethodMagicsTrait;
use BRCas\CA\Domain\Validation\DomainValidation;
use BRCas\CA\Domain\ValueObject\Uuid;
use BRCas\MV\Domain\Enum\CastMemberType;
use DateTime;

class CastMember
{
    use MethodMagicsTrait;

    public function __construct(
        protected string $name,
        protected CastMemberType $type,
        protected bool $isActive = true,
        protected ?Uuid $id = null,
        protected ?DateTime $createdAt = null,
    ) {
        $this->id = $this->id ?: Uuid::make();
        $this->createdAt = $this->createdAt ?: new DateTime();

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

    public function update(string $name)
    {
        $this->name = $name;
        $this->validate();
    }

    protected function validate(): void
    {
        DomainValidation::make($this->name)
            ->strMinLength()
            ->strMaxLength();
    }
}

<?php

declare(strict_types=1);

namespace BRCas\MV\Domain\Entity;

use BRCas\CA\Domain\Traits\MethodMagicsTrait;
use BRCas\CA\Domain\Validation\DomainValidation;
use BRCas\CA\Domain\ValueObject\Uuid;
use DateTime;

class Genre
{
    use MethodMagicsTrait;

    public function __construct(
        protected string $name,
        protected array $categories = [],
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

    public function addCategory(string $category){
        array_push($this->categories, $category);
    }

    public function removeCategory(string $category)
    {
        if (array_search($category, $this->categories) !== false) {
            unset($this->categories[array_search($category, $this->categories)]);
            $this->categories = array_values($this->categories);
        }
    }

    protected function validate(): void
    {
        DomainValidation::make($this->name)
            ->strMinLength()
            ->strMaxLength();
    }
}

<?php

declare(strict_types=1);

namespace Core\UseCases\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;

class CreateCategoryUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\CreateCategoryInput $input): DTO\CreateCategoryOutput
    {
        $domain = new Category(
            name: $input->name,
            description: $input->description,
            isActive: $input->isActive,
        );

        $newDomain = $this->repository->insert($domain);

        return new DTO\CreateCategoryOutput(
            id: $newDomain->id(),
            name: $newDomain->name,
            is_active: $newDomain->isActive,
        );
    }
}

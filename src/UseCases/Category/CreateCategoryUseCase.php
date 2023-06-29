<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Category;

use BRCas\MV\Domain\Entity\Category;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;

class CreateCategoryUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\CreateCategory\Input $input): DTO\CreateCategory\Output
    {
        $domain = new Category(
            name: $input->name,
            description: $input->description,
            isActive: $input->isActive,
        );

        $newDomain = $this->repository->insert($domain);

        return new DTO\CreateCategory\Output(
            id: $newDomain->id(),
            name: $newDomain->name,
            description: $newDomain->description,
            is_active: $newDomain->isActive,
            created_at: $newDomain->createdAt(),
        );
    }
}

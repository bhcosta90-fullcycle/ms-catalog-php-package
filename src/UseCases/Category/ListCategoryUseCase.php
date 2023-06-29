<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Category;

use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;

class ListCategoryUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\CategoryInput $input): DTO\CategoryOutput
    {
        $domain = $this->repository->getById($input->id);
        return new DTO\CategoryOutput(
            id: $domain->id(),
            name: $domain->name,
            description: $domain->description,
            is_active: $domain->isActive,
            created_at: $domain->createdAt(),
        );
    }
}

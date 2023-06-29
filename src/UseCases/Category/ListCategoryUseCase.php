<?php

declare(strict_types=1);

namespace Core\UseCases\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;

class ListCategoryUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\ListCategoryInput $input): DTO\ListCategoryOutput
    {
        $domain = $this->repository->find($input->id);
        return new DTO\ListCategoryOutput(
            id: $domain->id(),
            name: $domain->name,
            isActive: $domain->isActive,
        );
    }
}

<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Category;

use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;

class UpdateCategoryUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\UpdateCategory\Input $input): DTO\UpdateCategory\Output
    {
        $domain = $this->repository->getById($input->id);

        if ($input->isActive) {
            $domain->enable();
        } else {
            $domain->disable();
        }

        $domain->update(name: $input->name, description: $input->description);

        $this->repository->update($domain);

        return new DTO\UpdateCategory\Output(
            id: $domain->id(),
            name: $domain->name,
            is_active: $domain->isActive,
            created_at: $domain->createdAt(),
        );
    }
}

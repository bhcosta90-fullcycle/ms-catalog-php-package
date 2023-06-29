<?php

declare(strict_types=1);

namespace Core\UseCases\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;

class UpdateCategoryUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\UpdateCategoryInput $input): DTO\UpdateCategoryOutput
    {
        $domain = $this->repository->find($input->id);

        if ($input->isActive) {
            $domain->enable();
        } else {
            $domain->disable();
        }

        $domain->update(name: $input->name, description: $input->description);

        $newDomain = $this->repository->update($domain);

        return new DTO\UpdateCategoryOutput(
            id: $newDomain->id(),
            name: $newDomain->name,
            is_active: $newDomain->isActive,
        );
    }
}

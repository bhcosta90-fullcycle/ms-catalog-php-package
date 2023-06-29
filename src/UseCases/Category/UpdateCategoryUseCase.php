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

        $newDomain = $this->repository->update($domain);

        return new DTO\UpdateCategory\Output(
            id: $newDomain->id(),
            name: $newDomain->name,
            is_active: $newDomain->isActive,
        );
    }
}

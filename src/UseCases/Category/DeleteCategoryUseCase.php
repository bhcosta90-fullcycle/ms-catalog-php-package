<?php

declare(strict_types=1);

namespace Core\UseCases\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;

class DeleteCategoryUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\DeleteCategory\Input $input): DTO\DeleteCategory\Output
    {
        $domain = $this->repository->find($input->id);
        $success = $this->repository->delete($domain);

        return new DTO\DeleteCategory\Output(
            success: $success,
        );
    }
}

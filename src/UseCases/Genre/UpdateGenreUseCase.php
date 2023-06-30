<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Genre;

use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\UseCase\DatabaseTransactionInterface;
use BRCas\MV\Domain\Entity\Genre;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;
use BRCas\MV\Domain\Repository\GenreRepositoryInterface;
use Throwable;

class UpdateGenreUseCase
{
    public function __construct(
        protected GenreRepositoryInterface $repository,
        protected DatabaseTransactionInterface $transaction,
        protected CategoryRepositoryInterface $category,
    ) {
        //
    }

    public function execute(DTO\UpdateGenre\Input $input): DTO\GenreOutput
    {
        try {
            $domain = $this->repository->getById($input->id);
            $domain->update(name: $input->name);

            foreach ($input->categories as $categoryId) {
                $domain->addCategory($categoryId);
            }

            $this->validateCategories($input->categories);

            $newDomain = $this->repository->update($domain);
            $this->transaction->commit();
            return new DTO\GenreOutput(
                id: $newDomain->id(),
                name: $newDomain->name,
                categories: $newDomain->categories,
                is_active: $newDomain->isActive,
                created_at: $newDomain->createdAt(),
            );
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }

    protected function validateCategories(array $categories = [])
    {
        $categoriesDb = $this->category->getIdsByListId($categories);

        $arrayDiff = array_diff($categories, array_keys($categoriesDb->items()));

        if (count($arrayDiff)) {
            $message = sprintf(
                "%s %s not found",
                count($arrayDiff) > 1 ? "Categories" : "Category",
                implode(', ', $arrayDiff)
            );

            throw new EntityNotFoundException($message);
        }

        // if ($categoriesDb->total() != count($categories)) {
        //     throw new EntityNotFoundException('Categories not found.');
        // }
    }
}

<?php

namespace BRCas\MV\UseCases\Video;

use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\UseCase\DatabaseTransactionInterface;
use BRCas\CA\UseCase\FileStorageInterface;
use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\Rating;
use BRCas\MV\Domain\Event\VideoCreateEvent;
use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;
use BRCas\MV\Domain\Repository\GenreRepositoryInterface;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface;
use Throwable;

class CreateVideoUseCase
{
    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected CategoryRepositoryInterface $repositoryCategory,
        protected CastMemberRepositoryInterface $repositoryCastMember,
        protected GenreRepositoryInterface $repositoryGenre,
        protected DatabaseTransactionInterface $transaction,
        protected FileStorageInterface $storage,
        protected Interfaces\VideoEventManagerInterface $eventManager,
    ) {
        //
    }

    public function execute(DTO\CreateVideoInput $input): DTO\VideoOutput
    {
        $domain = $this->createEntity($input);

        try {
            $this->repository->insert($domain);

            if ($this->storeMedia($domain->id(), $input->videoFile)) {
                $this->eventManager(new VideoCreateEvent($domain));
            }

            $this->transaction->commit();

            return new DTO\VideoOutput(
                id: $domain->id(),
                title: $domain->title,
                description: $domain->description,
                yearLaunched: $domain->yearLaunched,
                duration: $domain->duration,
                opened: $domain->opened,
                rating: $domain->rating->value,
                categories: $domain->categories,
                genres: $domain->genres,
                cast_members: $domain->castMembers,
                created_at: $domain->createdAt(),
            );

        } catch(Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }

    protected function createEntity(DTO\CreateVideoInput $input): Video
    {
        $domain = new Video(
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: $input->opened,
            rating: Rating::from($input->rating)
        );

        $this->validateCategories($input->categories);
        $this->validateGenres($input->genres);
        $this->validateCastMember($input->castMembers);

        foreach ($input->categories as $category) {
            $domain->addCategory($category);
        }

        foreach ($input->genres as $genres) {
            $domain->addGenre($genres);
        }

        foreach ($input->castMembers as $castMember) {
            $domain->addCastMember($castMember);
        }

        return $domain;
    }

    protected function storeMedia(string $path, ?array $media = null): ?string
    {
        if ($media) {
            return $this->storage->store(path: $path, file: $media);
        }

        return null;
    }

    protected function validateCategories(array $categories = [])
    {
        $categoriesDb = $this->repositoryCategory->getIdsByListId($categories);

        $arrayDiff = array_diff($categories, array_keys($categoriesDb->items()));

        if (count($arrayDiff)) {
            $message = sprintf(
                "%s %s not found",
                count($arrayDiff) > 1 ? "Categories" : "Category",
                implode(', ', $arrayDiff)
            );

            throw new EntityNotFoundException($message);
        }
    }

    protected function validateGenres(array $genres = [])
    {
        $genresDb = $this->repositoryGenre->getIdsByListId($genres);

        $arrayDiff = array_diff($genres, array_keys($genresDb->items()));

        if (count($arrayDiff)) {
            $message = sprintf(
                "%s %s not found",
                count($arrayDiff) > 1 ? "Genre" : "Genres",
                implode(', ', $arrayDiff)
            );

            throw new EntityNotFoundException($message);
        }
    }

    protected function validateCastMember(array $castMember = [])
    {
        $castMemberDb = $this->repositoryCastMember->getIdsByListId($castMember);

        $arrayDiff = array_diff($castMember, array_keys($castMemberDb->items()));

        if (count($arrayDiff)) {
            $message = sprintf(
                "%s %s not found",
                count($arrayDiff) > 1 ? "Cast member" : "Cast members",
                implode(', ', $arrayDiff)
            );

            throw new EntityNotFoundException($message);
        }
    }
}

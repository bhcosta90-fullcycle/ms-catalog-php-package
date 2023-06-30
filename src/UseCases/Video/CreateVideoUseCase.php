<?php

namespace BRCas\MV\UseCases\Video;

use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\UseCase\DatabaseTransactionInterface;
use BRCas\CA\UseCase\FileStorageInterface;
use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\MediaStatus;
use BRCas\MV\Domain\Enum\Rating;
use BRCas\MV\Domain\Event\VideoCreateEvent;
use BRCas\MV\Domain\Repository\CastMemberRepositoryInterface;
use BRCas\MV\Domain\Repository\CategoryRepositoryInterface;
use BRCas\MV\Domain\Repository\GenreRepositoryInterface;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface;
use BRCas\MV\Domain\ValueObject\Image;
use BRCas\MV\Domain\ValueObject\Media;
use Throwable;

class CreateVideoUseCase
{
    protected Video $entity;
    
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
        try {
            $this->createEntity($input);
            $files = $this->store($input);

            $this->repository->insert($this->entity);
            $this->transaction->commit();

            return $this->output();
        } catch (Throwable $e) {
            $this->transaction->rollback();

            foreach ($files ?? [] as $file) {
                $this->storage->delete($file);
            }

            throw $e;
        }
    }

    protected function createEntity(DTO\CreateVideoInput $input): Video
    {
        $this->entity = new Video(
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: $input->opened,
            rating: Rating::from($input->rating)
        );

        $this->validateAllIds($input);

        foreach ($input->categories as $category) {
            $this->entity->addCategory($category);
        }

        foreach ($input->genres as $genres) {
            $this->entity->addGenre($genres);
        }

        foreach ($input->castMembers as $castMember) {
            $this->entity->addCastMember($castMember);
        }

        return $this->entity;
    }

    protected function store($input): array
    {
        $response = [];

        if ($path = $this->storeFile($this->entity->id(), $input->videoFile)) {
            $media = new Media(path: $path, status: MediaStatus::PENDING);
            $this->entity->setVideoFile($media);
            $response[] = $path;
        }

        if ($path = $this->storeFile($this->entity->id(), $input->trailerFile)) {
            $media = new Media(path: $path, status: MediaStatus::PENDING);
            $this->entity->setTrailerFile($media);
            $response[] = $path;
        }

        if ($path = $this->storeFile($this->entity->id(), $input->bannerFile)) {
            $this->entity->setBannerFile(new Image(image: $path));
            $response[] = $path;
        }

        if ($path = $this->storeFile($this->entity->id(), $input->thumbFile)) {
            $this->entity->setThumbFile(new Image(image: $path));
            $response[] = $path;
        }

        if ($path = $this->storeFile($this->entity->id(), $input->thumbHalf)) {
            $this->entity->setThumbHalf(new Image(image: $path));
            $response[] = $path;
        }

        return $response;
    }

    protected function storeFile(string $path, ?array $media = null): ?string
    {
        if ($media) {
            return $this->storage->store(path: $path, file: $media);
        }

        return null;
    }

    protected function output(): DTO\VideoOutput
    {
        return new DTO\VideoOutput(
            id: $this->entity->id(),
            title: $this->entity->title,
            description: $this->entity->description,
            yearLaunched: $this->entity->yearLaunched,
            duration: $this->entity->duration,
            opened: $this->entity->opened,
            rating: $this->entity->rating->value,
            categories: $this->entity->categories,
            genres: $this->entity->genres,
            cast_members: $this->entity->castMembers,
            thumb_file: $this->entity->thumbFile()?->path(),
            thumb_half: $this->entity->thumbHalf()?->path(),
            banner_file: $this->entity->bannerFile()?->path(),
            trailer_file: $this->entity->trailerFile()?->path,
            video_file: $this->entity->trailerFile()?->path,
            created_at: $this->entity->createdAt(),
        );
    }

    protected function validateAllIds($input) {
        $this->validateIds($input->categories, $this->repositoryCategory, "Category", "Categories");
        $this->validateIds($input->genres, $this->repositoryGenre, "Genre");
        $this->validateIds($input->castMembers, $this->repositoryCastMember, "Cast member");
    }

    protected function validateIds(array $ids, $repository, string $singular, ?string $plural = null)
    {
        $idsDb = $repository->getIdsByListId($ids);

        $arrayDiff = array_diff($ids, array_keys($idsDb->items()));

        if (count($arrayDiff)) {
            $message = sprintf(
                "%s %s not found",
                count($arrayDiff) > 1 ? ($plural ? $plural : ($singular . 's')) : $singular,
                implode(', ', $arrayDiff)
            );

            throw new EntityNotFoundException($message);
        }
    }
}

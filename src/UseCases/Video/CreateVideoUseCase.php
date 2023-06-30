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
use BRCas\MV\UseCases\Video\Builder\BuilderVideo;
use Throwable;

class CreateVideoUseCase
{
    protected BuilderVideo $builder;
    
    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected CategoryRepositoryInterface $repositoryCategory,
        protected CastMemberRepositoryInterface $repositoryCastMember,
        protected GenreRepositoryInterface $repositoryGenre,
        protected DatabaseTransactionInterface $transaction,
        protected FileStorageInterface $storage,
        protected Interfaces\VideoEventManagerInterface $eventManager,
    ) {
        $this->builder = new BuilderVideo();
    }

    public function execute(DTO\CreateVideoInput $input): DTO\VideoOutput
    {
        try {
            $this->validateAllIds($input);
            $this->builder->createEntity($input);
            $files = $this->store($input);
            
            $this->repository->insert($this->builder->getEntity());
            $this->transaction->commit();

            if (!empty($files['video-file']) || !empty($files['banner-file'])) {
                $this->eventManager->dispatch(new VideoCreateEvent($this->entity));
            }

            return $this->output();
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
    }

    protected function store($input): array
    {
        $pathFile = $this->builder->getEntity()->id();
        $response = [];

        if ($path = $this->storeFile($pathFile, $input->videoFile)) {
            $this->builder->addMediaVideo($path, MediaStatus::PROCESSING);
            $response['video-file'] = $path;
        }

        if ($path = $this->storeFile($pathFile, $input->trailerFile)) {
            $this->builder->addMediaTrailer($path, MediaStatus::PROCESSING);
            $response['trailer-file'] = $path;
        }

        if ($path = $this->storeFile($pathFile, $input->bannerFile)) {
            $this->builder->addImageBanner($path);
            $response['banner-file'] = $path;
        }

        if ($path = $this->storeFile($pathFile, $input->thumbFile)) {
            $this->builder->addImageThumb($path);
            $response['thumb-file'] = $path;
        }

        if ($path = $this->storeFile($pathFile, $input->thumbHalf)) {
            $this->builder->addImageThumbHalf($path);
            $response['thumb-half'] = $path;
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
            id: $this->builder->getEntity()->id(),
            title: $this->builder->getEntity()->title,
            description: $this->builder->getEntity()->description,
            yearLaunched: $this->builder->getEntity()->yearLaunched,
            duration: $this->builder->getEntity()->duration,
            opened: $this->builder->getEntity()->opened,
            rating: $this->builder->getEntity()->rating->value,
            categories: $this->builder->getEntity()->categories,
            genres: $this->builder->getEntity()->genres,
            cast_members: $this->builder->getEntity()->castMembers,
            thumb_file: $this->builder->getEntity()->thumbFile()?->path(),
            thumb_half: $this->builder->getEntity()->thumbHalf()?->path(),
            banner_file: $this->builder->getEntity()->bannerFile()?->path(),
            trailer_file: $this->builder->getEntity()->trailerFile()?->path,
            video_file: $this->builder->getEntity()->trailerFile()?->path,
            created_at: $this->builder->getEntity()->createdAt(),
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

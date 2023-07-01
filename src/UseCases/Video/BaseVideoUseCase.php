<?php

namespace BRCas\MV\UseCases\Video;

use BRCas\CA\Domain\Exceptions\EntityNotFoundException;
use BRCas\CA\UseCase\{DatabaseTransactionInterface, FileStorageInterface};
use BRCas\MV\Domain\Builder\Video\CreateBuilderVideo;
use BRCas\MV\Domain\Enum\MediaStatus;
use BRCas\MV\Domain\Repository\{
    CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface
};

abstract class BaseVideoUseCase
{
    protected CreateBuilderVideo $builder;

    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected CategoryRepositoryInterface $repositoryCategory,
        protected CastMemberRepositoryInterface $repositoryCastMember,
        protected GenreRepositoryInterface $repositoryGenre,
        protected DatabaseTransactionInterface $transaction,
        protected FileStorageInterface $storage,
        protected Interfaces\VideoEventManagerInterface $eventManager,
    ) {
        $this->builder = new CreateBuilderVideo();
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

    protected function validateAllIds($input)
    {
        if (count($input->categories)) {
            $this->validateIds($input->categories, $this->repositoryCategory, "Category", "Categories");
        }

        if (count($input->genres)) {
            $this->validateIds($input->genres, $this->repositoryGenre, "Genre");
        }

        if (count($input->castMembers)) {
            $this->validateIds($input->castMembers, $this->repositoryCastMember, "Cast member");
        }
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

<?php

namespace BRCas\MV\UseCases\Video;

use BRCas\MV\Domain\Enum\MediaStatus;
use BRCas\MV\Domain\Repository\VideoRepositoryInterface;
use BRCas\MV\Domain\ValueObject\Media;

class UpdatePathMediaUseCase
{
    public function __construct(
        protected VideoRepositoryInterface $repository,
    ) {
        //
    }

    public function execute(DTO\UpdatePathMediaInput $input): DTO\VideoOutput
    {
        $entity = $this->repository->getById($input->id);

        $exec = 0;
        if (($data = $entity->videoFile()) && $input->type === 'video') {
            $exec++;
            $entity->setVideoFile(new Media(
                path: $data->path,
                status: MediaStatus::COMPLETE,
                encoded: $input->path,
            ));
        }

        if (($data = $entity->trailerFile()) && $input->type === 'trailer') {
            $exec++;
            $entity->setTrailerFile(new Media(
                path: $data?->path,
                status: MediaStatus::COMPLETE,
                encoded: $input->path,
            ));
        }

        $response = $exec ? $this->repository->updateMedia($entity) : $entity;

        return new DTO\VideoOutput(
            id: $response->id(),
            title: $response->title,
            description: $response->description,
            year_launched: $response->yearLaunched,
            duration: $response->duration,
            opened: $response->opened,
            rating: $response->rating->value,
            created_at: $response->createdAt(),
            categories: $response->categories,
            genres: $response->genres,
            cast_members: $response->castMembers,
            thumb_file: $response->thumbFile()?->path(),
            thumb_half: $response->thumbHalf()?->path(),
            banner_file: $response->bannerFile()?->path(),
            trailer_file: $response->trailerFile()?->path,
            video_file: $response->videoFile()?->path,
        );
    }
}

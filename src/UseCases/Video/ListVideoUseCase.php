<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Video;

use BRCas\MV\Domain\Repository\VideoRepositoryInterface;

class ListVideoUseCase
{
    public function __construct(
        protected VideoRepositoryInterface $repository
    ) {
        //
    }

    public function execute(DTO\ListVideoInput $input): DTO\VideoOutput
    {
        $entity = $this->repository->getById($input->id);
        
        return new DTO\VideoOutput(
            id: $entity->id(),
            title: $entity->title,
            description: $entity->description,
            year_launched: $entity->yearLaunched,
            duration: $entity->duration,
            opened: $entity->opened,
            rating: $entity->rating->value,
            created_at: $entity->createdAt(),
            categories: $entity->categories,
            genres: $entity->genres,
            cast_members: $entity->castMembers,
            thumb_file: $entity->thumbFile()?->path(),
            thumb_half: $entity->thumbHalf()?->path(),
            banner_file: $entity->bannerFile()?->path(),
            trailer_file: $entity->trailerFile()?->path,
            video_file: $entity->videoFile()?->path,
        );
    }
}

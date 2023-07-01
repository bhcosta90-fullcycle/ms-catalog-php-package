<?php

namespace BRCas\MV\UseCases\Video;

use BRCas\MV\Domain\Event\VideoCreateEvent;
use Throwable;

class CreateVideoUseCase extends BaseVideoUseCase
{
    public function execute(DTO\CreateVideoInput $input): DTO\VideoOutput
    {
        try {
            $this->validateAllIds($input);
            $this->builder->createEntity($input);
            $files = $this->store($input);
            
            $this->repository->insert($this->builder->getEntity());
            $this->transaction->commit();

            if (!empty($files['video-file']) || !empty($files['trailer-file'])) {
                $this->eventManager->dispatch(new VideoCreateEvent($this->builder->getEntity()));
            }

            return $this->output();
        } catch (Throwable $e) {
            $this->transaction->rollback();
            throw $e;
        }
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
            video_file: $this->builder->getEntity()->videoFile()?->path,
            created_at: $this->builder->getEntity()->createdAt(),
        );
    }
}

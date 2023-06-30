<?php

namespace BRCas\MV\UseCases\Video\Builder;

use BRCas\MV\Domain\Entity\Video;
use BRCas\MV\Domain\Enum\MediaStatus;
use BRCas\MV\UseCases\Video\Interfaces\BuilderVideoInterface;

class BuilderVideo implements BuilderVideoInterface
{
    protected ?Video $entity = null;

    public function __construct()
    {
        $this->reset();
    }

    public function createEntity(object $input): Video
    {
        $this->entity = new Video(
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: $input->opened,
            rating: Rating::from($input->rating)
        );

        return $this->entity;
    }

    public function addMediaVideo($path, MediaStatus $status): self
    {
        $media = new Media(path: $path, status: MediaStatus::PENDING);
        $this->entity->setVideoFile($media);
        return $this;
    }

    public function addMediaTrailer($path, MediaStatus $status): self
    {
        $media = new Media(path: $path, status: MediaStatus::PENDING);
        $this->entity->setTrailerFile($media);

        return $this;
    }

    public function addImageThumb($path): self
    {
        $this->entity->setThumbFile(new Image(image: $path));
        return $this;
    }

    public function addImageThumbHalf($path): self
    {
        $this->entity->setThumbHalf(new Image(image: $path));
        return $this;
    }

    public function addImageBanner($path): self
    {
        $this->entity->setBannerFile(new Image(image: $path));
        return $this;
    }

    public function getEntity(): Video
    {
        return $this->entity;
    }

    protected function reset()
    {
        $this->entity = null;
    }
}

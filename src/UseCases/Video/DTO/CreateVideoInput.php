<?php

namespace BRCas\MV\UseCases\Video\DTO;

class CreateVideoInput
{
    public function __construct(
        public string $title,
        public string $description,
        public int $yearLaunched,
        public int $duration,
        public bool $opened,
        public string $rating,
        public array $categories = [],
        public array $genres = [],
        public array $castMembers = [],
        public array $thumbFile = [],
        public array $thumbHalf = [],
        public array $bannerFile = [],
        public array $trailerFile = [],
        public array $videoFile = [],
    ) {
        //
    }
}

<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Video\DTO;

class UpdateVideoInput
{
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public array $categories = [],
        public array $genres = [],
        public array $castMembers = [],
        public ?array $thumbFile = [],
        public ?array $thumbHalf = [],
        public ?array $bannerFile = [],
        public ?array $trailerFile = [],
        public ?array $videoFile = [],
    ) {
        //
    }
}

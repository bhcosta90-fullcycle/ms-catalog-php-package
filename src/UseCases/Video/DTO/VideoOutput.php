<?php

namespace BRCas\MV\UseCases\Video\DTO;

class VideoOutput
{
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public int $year_launched,
        public int $duration,
        public bool $opened,
        public string $rating,
        public string $created_at,
        public array $categories,
        public array $genres,
        public array $cast_members,
        public ?string $thumb_file,
        public ?string $thumb_half,
        public ?string $banner_file,
        public ?string $trailer_file,
        public ?string $video_file,
    ) {
        //
    }
}

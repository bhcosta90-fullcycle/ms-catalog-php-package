<?php

namespace BRCas\MV\UseCases\Video\DTO;

class VideoOutput
{
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public int $yearLaunched,
        public int $duration,
        public bool $opened,
        public string $rating,
        public string $created_at,
    ) {
        //
    }
}

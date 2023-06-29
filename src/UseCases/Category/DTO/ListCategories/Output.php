<?php

declare(strict_types=1);

namespace BRCas\MV\UseCases\Category\DTO\ListCategories;

class Output
{
    public function __construct(
        public array $items,
        public int $total,
        public int $last_page,
        public int $first_page,
        public int $current_page,
        public int $per_page,
        public int $to,
        public int $from,
    ) {
        //
    }
}

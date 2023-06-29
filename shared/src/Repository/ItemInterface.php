<?php

declare(strict_types=1);

namespace BRCas\CA\Repository;

interface ItemInterface
{
    /** @return stdClass[] */
    public function items(): array;

    public function total(): int;
}

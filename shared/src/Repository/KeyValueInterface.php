<?php

declare(strict_types=1);

namespace BRCas\CA\Repository;

interface KeyValueInterface
{
    /** @return stdClass[] */
    public function items(): array;
}

<?php

declare(strict_types=1);

namespace BRCas\CA\Repository;

interface KeyValueInterface
{
    /**
     * Function that takes an associative array of key and value and performs an operation.
     *
     * @return stdClass The array with key and value associative.
     *   Example: ['key1' => 'value1', 'key2' => 'value2']
     */
    public function items(): array;
}

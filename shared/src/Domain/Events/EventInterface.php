<?php

declare(strict_types=1);

namespace BRCas\CA\Domain\Events;

interface EventInterface
{
    public function name(): string;

    public function payload(): DTO\PayloadEventOutputInterface;
}

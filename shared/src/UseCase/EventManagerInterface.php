<?php

declare(strict_types=1);

namespace BRCas\CA\UseCase;

use BRCas\CA\Domain\Events\EventInterface;

interface EventManagerInterface
{
    public function dispatch(EventInterface $event): void;
}

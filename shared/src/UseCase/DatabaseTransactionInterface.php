<?php

declare(strict_types=1);

namespace BRCas\CA\UseCase;

interface DatabaseTransactionInterface
{
    public function commit(): void;

    public function rollback(): void;
}

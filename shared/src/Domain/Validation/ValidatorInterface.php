<?php

declare(strict_types=1);

namespace BRCas\CA\Domain\Validation;

use BRCas\CA\Domain\Abstracts\EntityAbstract;

interface ValidatorInterface
{
    public function validate(EntityAbstract $entity): void;
}

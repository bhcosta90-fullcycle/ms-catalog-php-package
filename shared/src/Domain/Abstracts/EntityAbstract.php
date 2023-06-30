<?php

declare(strict_types=1);

namespace BRCas\CA\Domain\Abstracts;

use BRCas\CA\Domain\Notification\ValidationNotification;
use BRCas\CA\Domain\Traits\MethodMagicsTrait;

class EntityAbstract
{
    use MethodMagicsTrait;

    public function __construct(
        protected ValidationNotification $notification = new ValidationNotification)
    {
        //
    }
}

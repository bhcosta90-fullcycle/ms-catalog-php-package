<?php

declare(strict_types=1);

namespace BRCas\MV\Domain\Factory;

use BRCas\CA\Domain\Validation\ValidatorInterface;
use BRCas\MV\Domain\Validation\VideoRakitValidation;

class VideoValidatorFactory
{
    public static function make(): ValidatorInterface
    {
        return new VideoRakitValidation;
    }
}

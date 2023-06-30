<?php

declare(strict_types=1);

namespace BRCas\MV\Domain\Validation;

use BRCas\CA\Domain\Abstracts\EntityAbstract;
use BRCas\CA\Domain\Validation\ValidatorInterface;
use BRCas\CA\Validation\RakitValidator;
use BRCas\MV\Domain\Entity\Video;

class VideoRakitValidation implements ValidatorInterface
{
    public function validate(EntityAbstract $entity): void
    {
        $data = $this->convertEntityForArray($entity);

        $errors = RakitValidator::make($data, [
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:3|max:255',
            'yearLaunched' => 'required|integer',
            'duration' => 'required|integer',
        ]);

        if ($errors) {
            foreach ($errors as $error) {
                $entity->notification->addError('video', $error);
            }
        }
    }

    protected function convertEntityForArray(Video $entity): array
    {
        return [
            'title' => $entity->title,
            'description' => $entity->description,
            'yearLaunched' => $entity->yearLaunched,
            'duration' => $entity->duration,
        ];
    }
}

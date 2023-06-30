<?php

declare(strict_types=1);

namespace BRCas\CA\Validation;

use Rakit\Validation\Validator;

class RakitValidator
{
    public static function make(array $data, array $rules): array
    {
        $validation = (new Validator())->validate($data, $rules);
        return $validation->errors()->all();       
    }
}

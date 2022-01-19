<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait FormRequestTrait
{
    /**
     * @param array $rules
     * @return array
     * @throws ValidationException
     */
    protected function validate(array $rules): array
    {
        $validator = Validator::make(\request()->all(), $rules);

        if ($validator->errors()->any()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}

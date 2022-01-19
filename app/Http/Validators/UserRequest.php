<?php

namespace App\Http\Validators;

use App\Exceptions\ApiTokenException;
use App\Traits\FormRequestTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserRequest
{
    use FormRequestTrait;

    public function validateRegister(): array
    {
        return $this->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);
    }

    public function validateLogin(): array
    {
        return $this->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string'
        ]);
    }

    public function validateHeaders(array $headers)
    {
        try {
            Validator::validate($headers, [
                'userid' => 'required|integer|exists:users,id',
                'apitoken' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (password_get_info($value)['algo'] != PASSWORD_BCRYPT)
                            $fail("O campo {$attribute} está no formato inválido.");
                    }
                ],
            ]);
        } catch (ValidationException $e) {

            $errors = $e->errors();

            if (isset($errors['userid']))
                throw new ApiTokenException(implode(' ', $errors['userid']));

            if (isset($errors['apitoken']))
                throw new ApiTokenException(implode(' ', $errors['apitoken']));
        }
    }
}

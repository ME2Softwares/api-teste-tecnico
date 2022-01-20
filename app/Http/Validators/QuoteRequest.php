<?php

namespace App\Http\Validators;

use App\Traits\FormRequestTrait;

class QuoteRequest
{
    use FormRequestTrait;

    public function validateStore()
    {
        return $this->validate([
            'text' => 'required|string',
            'author' => 'required|string',
        ]);
    }
}

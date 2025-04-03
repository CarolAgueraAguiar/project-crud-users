<?php

namespace Application\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserForgotPasswordRequest extends FormRequest
{
    public function rules()
    {
        return ['email' => 'required|string|email'];
    }
}

<?php

namespace Application\User\Requests;

use Domain\User\Model\User;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function rules()
    {
        $userId = Auth::id();

        return [
            'name' => 'required|string|max:100',
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique(User::class, 'email')->ignore($userId)],
            'password' => [
                'sometimes',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'zip_code' => 'required|string|size:8',
            'address_number' => 'required|string|max:20',
            'address_complement' => 'nullable|string|max:100',
        ];
    }
}

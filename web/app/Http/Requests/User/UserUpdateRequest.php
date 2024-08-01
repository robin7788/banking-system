<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class UserUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user->id)],
            'dob' => 'required|date|before:tomorrow',
            'address_1' => 'required|string|max:150',
            'address_2' => 'nullable|string|max:150',
            'town' => 'required|string|max:150',
            'country' => 'required|string|max:150',
            'post_code' => 'required|string|max:20',
            'password' => ['nullable', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        ];
    }
}

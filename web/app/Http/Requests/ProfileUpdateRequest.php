<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'dob' => 'nullable|date|before:tomorrow',
            'address_1' => 'nullable|string|max:150',
            'address_2' => 'nullable|string|max:150',
            'town' => 'nullable|string|max:150',
            'country' => 'nullable|string|max:150',
            'post_code' => 'nullable|string|max:20',
        ];
    }
}

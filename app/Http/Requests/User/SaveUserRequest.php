<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $userId   = optional($this->route('user'))->id;
        $isUpdate = $userId !== null;

        return [
            'firstname'    => ['required', 'string', 'max:100'],
            'lastname'     => ['required', 'string', 'max:100'],
            'email'        => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'role'         => ['required', Rule::in(['superadmin', 'ngo_staff', 'mentor', 'learner'])],
            'ngo_id'       => ['nullable', 'integer', 'exists:ngos,id'],
            'is_approved'  => ['nullable', 'boolean'],
            'password'     => [$isUpdate ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ];
    }
}

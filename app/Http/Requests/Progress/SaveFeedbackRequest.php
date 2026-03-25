<?php

namespace App\Http\Requests\Progress;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveFeedbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $m = $this->input('material_id');
        if ($m === '' || $m === null) {
            $this->merge(['material_id' => null]);
        }
    }

    public function rules(): array
    {
        return [
            'learner_id'  => ['required', 'integer', 'exists:users,id'],
            'program_id'  => ['required', 'integer', 'exists:programs,id'],
            'material_id' => [
                'nullable',
                'integer',
                Rule::exists('learning_materials', 'id')->where('program_id', $this->input('program_id')),
            ],
            'content' => ['required', 'string', 'min:5', 'max:5000'],
        ];
    }
}

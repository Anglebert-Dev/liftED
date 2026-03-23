<?php

namespace App\Http\Requests\LearningMaterial;

use Illuminate\Foundation\Http\FormRequest;

class SaveMaterialRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $isUpdate = $this->route('material') !== null;

        if ($isUpdate) {
            return [
                'title'      => ['required', 'string', 'max:255'],
                'program_id' => ['required', 'integer', 'exists:programs,id'],
                'file'       => [
                    'nullable', 'file', 'max:102400',
                    'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,mp4,mov,avi,mkv,png,jpg,jpeg',
                ],
            ];
        }

        return [
            'program_id' => ['required', 'integer', 'exists:programs,id'],
            'files'      => ['required', 'array', 'min:1'],
            'files.*'    => [
                'required', 'file', 'max:102400',
                'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,mp4,mov,avi,mkv,png,jpg,jpeg',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'files.required'   => 'Please select at least one file.',
            'files.*.mimes'    => 'Allowed types: PDF, Word, PowerPoint, Excel, Video, Image.',
            'files.*.max'      => 'Each file must not exceed 100MB.',
        ];
    }
}

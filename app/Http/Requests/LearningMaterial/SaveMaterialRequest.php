<?php

namespace App\Http\Requests\LearningMaterial;

use App\Models\LearningMaterial\LearningMaterial;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->route('material') instanceof LearningMaterial;

        if ($isUpdate) {
            return [
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:5000'],
                'external_url' => ['nullable', 'string', 'url', 'max:2048'],
                'program_id' => ['required', 'integer', 'exists:programs,id'],
                'file' => [
                    'nullable', 'file', 'max:102400',
                    'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,mp4,mov,avi,mkv,png,jpg,jpeg',
                ],
            ];
        }

        return [
            'program_id' => ['required', 'integer', 'exists:programs,id'],
            'creation_mode' => ['required', Rule::in(['upload', 'link'])],
            'title' => ['required_if:creation_mode,link', 'nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'external_url' => ['required_if:creation_mode,link', 'nullable', 'string', 'url', 'max:2048'],
            'file' => [
                'nullable',
                'file',
                'max:102400',
                'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,mp4,mov,avi,mkv,png,jpg,jpeg',
            ],
            'files' => ['required_if:creation_mode,upload', 'nullable', 'array', 'min:1'],
            'files.*' => [
                'required',
                'file',
                'max:102400',
                'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,mp4,mov,avi,mkv,png,jpg,jpeg',
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $material = $this->route('material');
            if (! $material instanceof LearningMaterial) {
                return;
            }

            $effectiveUrl = trim((string) $this->input('external_url', ''));
            $effectiveFile = $this->hasFile('file') || $material->hasStoredFile();

            if ($effectiveUrl === '' && ! $effectiveFile) {
                $validator->errors()->add('external_url', 'Keep an uploaded file or an external link (or both).');
            }
        });
    }

    public function messages(): array
    {
        return [
            'files.required_if' => 'Please select at least one file.',
            'files.*.mimes' => 'Allowed types: PDF, Word, PowerPoint, Excel, Video, Image.',
            'files.*.max' => 'Each file must not exceed 100MB.',
            'external_url.url' => 'Enter a valid http(s) URL.',
            'external_url.required_if' => 'A link URL is required for this option.',
        ];
    }
}

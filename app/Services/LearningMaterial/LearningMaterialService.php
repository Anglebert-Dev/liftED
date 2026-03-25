<?php

namespace App\Services\LearningMaterial;

use App\Libraries\UploadLibrary;
use App\Models\LearningMaterial\LearningMaterial;
use App\Repositories\LearningMaterial\LearningMaterialRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LearningMaterialService extends BaseService
{
    public function __construct(private LearningMaterialRepository $repo) {}

    /**
     * Create multiple materials from uploaded files (batch upload).
     */
    public function saveBatch(Request $request): array
    {
        try {
            $saved = 0;

            foreach ($request->file('files', []) as $file) {
                $material = new LearningMaterial;
                $material->program_id = $request->input('program_id');
                $material->title = Str::title(
                    str_replace(['-', '_', '.'], ' ', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                );
                $material->type = UploadLibrary::guessType($file);
                $material->file_path = UploadLibrary::store($file, 'materials/'.$material->type);
                $this->repo->save($material);
                $saved++;
            }

            return $this->success(null, $saved.' material(s) uploaded successfully.');
        } catch (\Throwable $e) {
            return $this->failure('Could not upload materials: '.$e->getMessage());
        }
    }

    /**
     * Single material: external link (optional short description) and/or one uploaded file.
     */
    public function saveLinkMaterial(Request $request): array
    {
        try {
            $material = new LearningMaterial;
            $material->program_id = (int) $request->input('program_id');
            $material->title = $request->input('title');
            $material->description = $request->input('description');

            $url = trim((string) $request->input('external_url', ''));
            $material->external_url = $url === '' ? null : $url;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $material->type = UploadLibrary::guessType($file);
                $material->file_path = UploadLibrary::store($file, 'materials/'.$material->type);
            } else {
                $material->type = UploadLibrary::guessTypeFromUrl((string) $material->external_url);
                $material->file_path = null;
            }

            $this->repo->save($material);

            return $this->success($material, 'Material added.');
        } catch (\Throwable $e) {
            return $this->failure('Could not save material: '.$e->getMessage());
        }
    }

    /**
     * Update an existing material (single file + title).
     */
    public function save(Request $request, LearningMaterial $material): array
    {
        try {
            $material->title = $request->input('title');
            $material->description = $request->input('description');

            $url = trim((string) $request->input('external_url', ''));
            $material->external_url = $url === '' ? null : $url;

            if ($request->hasFile('file')) {
                if ($material->hasStoredFile()) {
                    UploadLibrary::delete($material->file_path);
                }
                $file = $request->file('file');
                $material->type = UploadLibrary::guessType($file);
                $material->file_path = UploadLibrary::store($file, 'materials/'.$material->type);
            }

            if (! $material->hasStoredFile() && $material->hasExternalUrl()) {
                $material->type = UploadLibrary::guessTypeFromUrl((string) $material->external_url);
            }

            $this->repo->save($material);

            return $this->success($material, 'Material updated.');
        } catch (\Throwable $e) {
            return $this->failure('Could not update material: '.$e->getMessage());
        }
    }

    public function delete(LearningMaterial $material): array
    {
        try {
            if ($material->hasStoredFile()) {
                UploadLibrary::delete($material->file_path);
            }
            $this->repo->delete($material);

            return $this->success(null, 'Material deleted.');
        } catch (\Throwable $e) {
            return $this->failure('Could not delete material.');
        }
    }
}

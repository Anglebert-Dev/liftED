<?php

namespace App\Libraries;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadLibrary
{
    /**
     * Store an uploaded file and return its stored path.
     *
     * @param  string  $directory  e.g. 'materials/pdf'
     * @return string stored path relative to the disk root
     */
    public static function store(UploadedFile $file, string $directory = 'materials'): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid().'.'.$extension;

        return $file->storeAs($directory, $filename, 'local');
    }

    /**
     * Delete a file from local disk.
     */
    public static function delete(string $path): bool
    {
        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->delete($path);
        }

        return false;
    }

    /**
     * Return a guessed type string for display purposes.
     */
    public static function guessType(UploadedFile $file): string
    {
        $mime = $file->getMimeType();

        return match (true) {
            str_contains($mime, 'pdf') => 'pdf',
            str_contains($mime, 'video') => 'video',
            str_contains($mime, 'image') => 'image',
            default => 'document',
        };
    }

    /**
     * Classify external resources for display (link, video hosting, etc.).
     */
    public static function guessTypeFromUrl(string $url): string
    {
        $lower = strtolower($url);

        return match (true) {
            str_contains($lower, 'youtube.com'),
            str_contains($lower, 'youtu.be'),
            str_contains($lower, 'vimeo.com') => 'video',
            default => 'link',
        };
    }
}

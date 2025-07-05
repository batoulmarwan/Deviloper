<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait Uploadfile
{
    public function storeFile(UploadedFile $file, string $folder, ?string $filename = null, string $disk = 'public_uploads'): string
    {
        $name = ($filename ?? time()) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $name, $disk);
        return $path;
    }
}

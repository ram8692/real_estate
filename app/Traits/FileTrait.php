<?php // FileTrait.php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FileTrait
{
    public function deleteFile($filePath)
    {
        // Check if not null and not empty
        if (!empty($filePath)) {
            // Delete the file from storage
            Storage::disk('public')->delete($filePath);
        }
    }
}

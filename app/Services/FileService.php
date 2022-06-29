<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class FileService
{
    public function storePublicImageFromInput($inputName, $path, $fileName)
    {
        if (request()->hasFile($inputName)) {
            $image = request()->file($inputName);
            $fileName = $fileName . '.' . $image->extension();
            $image->move($path, $fileName);
            $image_path = '/' . $path . $fileName;
        } else {
            $image_path = null;
        }

        return $image_path;
    }

    public function updatePublicImageFromInput($inputName, $path, $fileName, Model $model, $modelPathField,)
    {
        if (request()->hasFile($inputName)) {
            Log::info('1 - ' . __METHOD__ . ' ; ' . $model[$modelPathField] . ' ; ' . $model);
            $image = request()->file($inputName);
            $fileName = $fileName . '.' . $image->extension();
            if ($model[$modelPathField] && File::exists(public_path() . $model[$modelPathField])) {
                File::delete(public_path() . $model[$modelPathField]);
            }
            $image->move($path, $fileName);
            $model[$modelPathField] = "/" . $path . $fileName;
            Log::info('2 - ' . __METHOD__ . ' ; ' . $model[$modelPathField] . ' ; ' . $model);
        }
    }
}

<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic as Image;

class FileService
{
    public function storePublicImageFromInput($inputName, $path, $fileName, Model $model, $modelPathField)
    {
        if (request()->hasFile($inputName)) {
            $image = request()->file($inputName);
            $this->makeSmall($model, $image, $fileName, $path, $image->extension());
            $fullFileName = $fileName . '.' . $image->extension();
            $image->move($path, $fullFileName);
            $model[$modelPathField] = '/' . $path . $fullFileName;
        }
    }

    public function updatePublicImageFromInput($inputName, $path, $fileName, Model $model, $modelPathField)
    {
        if (request()->hasFile($inputName)) {
            $image = request()->file($inputName);
            $this->makeSmall($model, $image, $fileName, $path, $image->extension());
            $fileName = $fileName . '.' . $image->extension();
            if ($model[$modelPathField] && File::exists(public_path() . $model[$modelPathField])) {
                File::delete(public_path() . $model[$modelPathField]);
            }
            $image->move($path, $fileName);
            $model[$modelPathField] = '/' . $path . $fileName;
        }
    }

    private function makeSmall($model, $image, $fileName, $path, $extension)
    {
        $thumbnail = Image::make($image->getRealPath());

        if ($model instanceof \App\Models\Boardgame) {
            $thumbnail->resize(128, 128);
        }

        if ($model instanceof \App\Models\User) {
            $thumbnail->resize(70, 70);
        }

        if ($model instanceof \App\Models\Game) {
            $canvas = Image::canvas(170, 170);
            $thumbnail->resize(170, 170, function ($constraint) {
                $constraint->aspectRatio();
            });
            $canvas->insert($thumbnail, 'center');
            $thumbnail = $canvas;
        }

        $fullFilePath = $path . $fileName . '_thumb' . '.' . $extension;
        $thumbnail->save($fullFilePath);
        $model['thumbnail'] = '/' . $fullFilePath;
    }
}

<?php

namespace App\Helpers;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class ImageUploadHelper
{
    /**
     * Handle image upload.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $inputName
     * @param string $folder
     * @param int|null $resizeWidth
     * @param int|null $resizeHeight
     * @return string|null
     */
    public static function uploadImage($request, $inputName, $folder = 'uploads', $resizeWidth = 300, $resizeHeight = 300)
    {
        // Check if the file exists in the request
        if ($request->hasFile($inputName)) {
            // Get the uploaded file
            $file = $request->file($inputName);
            $extension = strtolower($file->getClientOriginalExtension());
            $fileName = time() . '.' . $extension;

            // Resize the image if needed
            $image = Image::make($file)->resize($resizeWidth, $resizeHeight);

            // Define the directory path
            $directory = 'images/' . $folder;

            // Create the folder if it doesn't exist
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            // Save the image
            $image->save($directory . '/' . $fileName);

            return $fileName;
        }

        return null;  // No image was uploaded
    }

    /**
     * Delete an old image from the server if it exists.
     *
     * @param string $imageName
     * @param string $folder
     */
    public static function deleteImage($imageName, $folder = 'uploads')
    {
        $filePath = 'images/' . $folder . '/' . $imageName;

        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }
}

<?php

namespace App\Trait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

trait FlieUploadTrait {
    // Upload Image
    function uploadImage(Request $request, $inputname, $path="/uploads") {
        if ($request->hasFile($inputname)) {
            $image = $request->{$inputname};
            $ext = $image->getClientOriginalExtension();
            $imageName = 'media_'.uniqid().'.'.$ext;

            $image->move(public_path($path), $imageName);

            return $path.'/'.$imageName;
        }
    }

    // Remove Image
    function removeImage(String $path) {
        if (!empty($path)) {
            $oldImagePath = public_path($path);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }
        }
    }

    // Upload Image Base64
    function uploadImageFromBase64($base64Image, $path = "/uploads") {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $data = substr($base64Image, strpos($base64Image, ',') + 1);
            $ext = strtolower($type[1]); // jpg, png, gif

            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                throw new \Exception('Invalid image type');
            }

            $data = base64_decode($data);
            if ($data === false) {
                throw new \Exception('Base64 decode failed');
            }

            $imageName = 'media_'.uniqid().'.'.$ext;
            $imagePath = public_path($path) . '/' . $imageName;

            // Create directory if not exists
            if (!file_exists(public_path($path))) {
                mkdir(public_path($path), 0755, true);
            }

            file_put_contents($imagePath, $data);

            return $path . '/' . $imageName;
        }

        return null;
    }

}

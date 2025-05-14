<?php

namespace App\Trait;
use Illuminate\Http\Request;

trait FlieUploadTrait {
    function uploadImage(Request $request, $inputname, $path="/uploads") {
        if ($request->hasFile($inputname)) {
            $image = $request->{$inputname};
            $ext = $image->getClientOriginalExtension();
            $imageName = 'media_'.uniqid().'.'.$ext;

            $image->move(public_path($path), $imageName);

            return $path.'/'.$imageName;
        }
    }
}

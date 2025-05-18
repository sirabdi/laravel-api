<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Trait\FlieUploadTrait;
use App\Models\ProductGallery;
use App\Models\Product;

class ApiProductGallery extends Controller
{
    use FlieUploadTrait;

    // Create New Gallery
    public function createGallery(int $productId, Request $request) {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Product Id Not Found!'
            ], 404);
        }

        $validator = Validator::make(data: $request->all(), rules: [
            'image' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 404);
        };

        $imageItems = $request->image;

        foreach ($imageItems as $imageItem) {
            $imagePath = $this->uploadImageFromBase64($imageItem);

            if ($imagePath) {
                ProductGallery::create([
                    'product_id' => $productId,
                    'image' => $imagePath
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Images uploaded successfully'
        ], 201);
    }
}

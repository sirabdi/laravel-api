<?php

namespace App\Http\Controllers\API;

use Str;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use App\Trait\FlieUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ApiProductCategoryController extends Controller
{
    use FlieUploadTrait;

    // Get All Product Category
    public function getAllCategories() {
        $productCategories = ProductCategory::get();

        if (!$productCategories) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No Product Category Found!'
            ], 400);
        };

        return response()->json([
            'status' => 'success',
            'count' => count($productCategories),
            'data' => $productCategories
        ], 200);
    }

    // New Product Category
    public function createCategory(Request $request) {
        $validator = Validator::make(data: $request->all(), rules: [
            'name' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 404);
        };

        $data['name'] = $request->name;
        $data['slug'] = Str::slug($request->name);
        $imagePath = $this->uploadImage($request, 'image');
        $data['image'] = isset($imagePath) ? $imagePath : '';

        ProductCategory::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Product Category Created Successfully!',
            'data' => $data
        ], 200);
    }

    // Edit Product Category
    public function editCategory(int $productCategoryId, Request $request) {
        $productCategory = ProductCategory::find($productCategoryId);

        if (!$productCategory) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No Product Category Found!'
            ], 404);
        };

        Log::info('Request Data:', $request->all());

        $validator = Validator::make(data: $request->all(), rules: [
            'name' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 400);
        };

        $productCategory->name = $request->name;
        $productCategory->slug = Str::slug($request->name);
        $imagePath = $this->uploadImage($request, 'image');

        // Delete old image if it exists
        if ($imagePath) {
            if (!empty($productCategory->image)) {
                $oldImagePath = public_path($productCategory->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
        }

        $productCategory['image'] = isset($imagePath) ? $imagePath : '';

        $productCategory->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product Category Updated!',
            'data' => $productCategory
        ], 200);
    }
}

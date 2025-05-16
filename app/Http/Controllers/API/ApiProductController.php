<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Trait\FlieUploadTrait;
use App\Models\ProductCategory;
use App\Models\Product;
use Str;

class ApiProductController extends Controller
{
    use FlieUploadTrait;

    // Get All Product Category
    public function getAllProducts() {
        $products = Product::get();

        if (!$products) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No Product Found!'
            ], 400);
        };

        return response()->json([
            'status' => 'success',
            'count' => count($products),
            'data' => $products
        ], 200);
    }

    // New Product
    public function createProduct(Request $request) {
        $validator = Validator::make(data: $request->all(), rules: [
            'name' => 'required',
            'price' => 'required',
            'category_id' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 404);
        };

        $category = ProductCategory::find($request->category_id);

        if(!$category){
            return response()->json([
                'status' => 'failed',
                'message' => 'Undefined category!'
            ], 404);
        };

        $data['name'] = $request->name;
        $data['price'] = $request->price;
        $data['category_id'] = $category->id;
        $data['slug'] = Str::slug($request->name);
        $data['description'] = $request->description;
        $imagePath = $this->uploadImageFromBase64($request->input('image'));
        $data['image'] = isset($imagePath) ? $imagePath : '';

        Product::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Product Created Successfully!',
            'data' => $data
        ], 200);
    }

    // Edit Product
    public function editProduct(int $productId, Request $request) {
        $product = Product::find($productId);

        if(!$product){
            return response()->json([
                'status' => 'failed',
                'message' => 'No Product Found!'
            ], 404);
        };

        $validator = Validator::make(data: $request->all(), rules: [
            'name' => 'required',
            'price' => 'required',
            'category_id' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 404);
        };

        if(!$product){
            return response()->json([
                'status' => 'failed',
                'message' => 'Undefined Product!'
            ], 404);
        };

        $product->name = $request->name;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->slug = Str::slug($request->name);
        $product->description = $request->description;
        $imagePath = $this->uploadImageFromBase64($request->input('image'));

        // Delete old image if it exists
        if ($imagePath) {
            $this->removeImage($product->image);
        }

        $product->image = isset($imagePath) ? $imagePath : '';

        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product Created Successfully!',
            'data' => $product
        ], 200);
    }

    // Delete Product Category
    public function deleteProduct(int $productId) {
        $product = Product::find($productId);

        if (!$product) {
            return response()->json([
                'status' => 'failed',
                'message' => 'No Product Found!'
            ], 404);
        }

        $this->removeImage($product->image);

        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Delete Data Successfully!'
        ], 200);
    }
}

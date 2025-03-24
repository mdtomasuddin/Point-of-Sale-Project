<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function CreateProduct(Request $request)
    {
        $user_id = $request->header('id');

        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'unit' => 'required',
            'category_id' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'unit' => $request->unit,
            'category_id' => $request->category_id,
            'user_id' => $user_id
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $filePath = 'uploads/' . $fileName;

            $image->move(public_path('uploads'), $fileName);
            $data['image'] = $filePath;
        }

        Product::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully'
        ]);
    }

    public function ProductList(Request $request)
    {
        $user_id = $request->header('id');
        $product = Product::where('user_id', $user_id)->get();
        return $product;
    }

    public function ProductById(Request $request)
    {
        $user_id = $request->header('id');
        $product = Product::where('user_id', $user_id)->where('id', $request->id)->first();
        return $product;
    }

    public function ProductUpdate(Request $request)
    {
        $user_id = $request->header('id');

        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'unit' => 'required',
            'category_id' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $product = Product::where('user_id', $user_id)->findOrFail($request->id);

        $product->name = $request->name;
        $product->price = $request->price;
        $product->unit = $request->unit;
        $product->category_id = $request->category_id;

        if ($request->hasFile('image')) {
            if ($product->image &&  file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $filePath = 'uploads/' . $fileName;

            $image->move(public_path('uploads'), $fileName);
            $product->image = $filePath;
        }

        $product->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Product Updated successfully'
        ]);
    }

    public function ProductDelete(Request $request, $id)
    {
        try {
            $user_id = $request->header('id');
            $product = Product::where('user_id', $user_id)->findOrFail($id);
            if ($product->image &&  file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            $product->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Product Deleted Successfully !',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Fail',
                'message' => $e->getMessage(),
            ]);
        }
    }
}

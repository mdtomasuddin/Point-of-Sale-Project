<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function ProductPage(Request $request)
    {
        $user_id = $request->header('id');
        $products = Product::where('user_id', $user_id)->with(['category','brand'])->latest()->get();
        return Inertia::render('ProductPage', [
            'products' => $products
        ]);
    }

    public function ProductSavePage(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->query('id');
        $product = Product::where('id', $product_id)->where('user_id', $user_id)->first();
        $categories = Category::where('user_id', $user_id)->get();
        $brands = Brand::where('user_id', $user_id)->get();
        return Inertia::render('ProductSavePage', [
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands
        ]);
    }

    public function CreateProduct(Request $request)
    {
        $user_id = $request->header('id');
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'unit' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'unit' => $request->unit,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
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

        $data = ['message' => 'Product created successfully', 'status' => true, 'error' => ''];
        return redirect()->back()->with($data);
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
            'brand_id' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $product = Product::where('user_id', $user_id)->findOrFail($request->id);

        $product->name = $request->name;
        $product->price = $request->price;
        $product->unit = $request->unit;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

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

        $data = ['message' => 'Product Updated successfully', 'status' => true, 'error' => ''];
        return redirect('/product-page')->with($data);
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

            $data = ['message' => "Product Deleted Successfully ", 'status' => true, 'error' => ''];
            return redirect('/product-page')->with($data);
        } catch (Exception $e) {
            $data = ['message' => $e->getMessage(), 'status' => false, 'error' => ''];
            return redirect('/product-page')->with($data);
        }
    }
}

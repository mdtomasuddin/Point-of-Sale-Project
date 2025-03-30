<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BrandController extends Controller
{

    public function BrandPage(Request $request)
    {
        $user_id = $request->header('id');
        $brands = Brand::where('user_id', $user_id)->get();
        return Inertia::render('BrandPage', [
            "brands" => $brands,
        ]);
    }
    public function BrandSavePage(Request $request)
    {
        $brand_id = $request->query('id');
        $user_id = $request->header('id');
        $brands = Brand::where('id', $brand_id)->where('user_id', $user_id)->first();
        return Inertia::render('BrandSavePage', [
            "brands" => $brands,
        ]);
    }

    public function CreateBrand(Request $request)
    {
        $user_id = $request->header('id');

        Brand::create([
            "name" => $request->input('name'),
            "user_id" => $user_id
        ]);

        $data = ['message' => "Brand Create Successfully ", 'status' => true, 'error' => ''];
        return redirect('/Brand-page')->with($data);
    }

    public function BrandList(Request $request)
    {
        $user_id = $request->header('id');
        $brands = Brand::where('user_id', $user_id)->get();
        return $brands;
    }


    public function BrandById(Request $request)
    {
        $user_id = $request->header('id');
        $brands = Brand::where('user_id', $user_id)->where('id', $request->id)->first();
        return $brands;
    }

    
    public function BrandUpdate(Request $request)
    {
        $user_id = $request->header('id');
        $id = $request->input('id');
        Brand::where('user_id', $user_id)->where('id', $id)->update([
            'name' => $request->input('name'),
        ]);
        $data = ['message' => "Brand Updated Successfully ", 'status' => true, 'error' => ''];
        return redirect('/Brand-page')->with($data);
    }


    public function BrandDelete(Request $request, $id)
    {
        $user_id = $request->header('id');
        Brand::where('user_id', $user_id)->where('id', $id)->delete();
        $data = ['message' => "Brand Deleted Successfully ", 'status' => true, 'error' => ''];
        return redirect('/Brand-page')->with($data);
    }
}

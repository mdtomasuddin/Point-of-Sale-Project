<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeshboardController extends Controller
{

    public function DeshboardPage(Request $request)
    {
        // $user = $request->header('email');
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'user Login successfully !',
        //     'user' => $user,
        // ]);
        return Inertia::render('DeshboardPage');
    }


    public function deshboardSummary(Request $request)
    {
        $user_id = $request->header('id');
        $product = Product::where('user_id', $user_id)->count();
        $customer = Customer::where('user_id', $user_id)->count();
        $category = Category::where('user_id', $user_id)->count();
        $invoice = Invoice::where('user_id', $user_id)->count();

        $total = Invoice::where('user_id', $user_id)->sum('total');
        $vat = Invoice::where('user_id', $user_id)->sum('vat');
        $payable = Invoice::where('user_id', $user_id)->sum('payable');
        $discount = Invoice::where('user_id', $user_id)->sum('discount');

        $data = [
            "product" => $product,
            "customer" => $customer,
            "category" => $category,
            "total" => $total,
            "vat" => $vat,
            "payable" => $payable,
            "discount" => $discount,
        ];
        return $data;
    }
}

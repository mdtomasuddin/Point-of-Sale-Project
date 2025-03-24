<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Pest\ArchPresets\Custom;

class CustomerController extends Controller
{
    public function Createcustomer(Request $request)
    {
        $user_id = $request->header('id');
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:customers,email',
            'mobile' => 'required',
        ]);

        Customer::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'user_id' => $user_id
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Customer Created Successfully !',
        ]);
    }
    public function customerList(Request $request)
    {
        $user_id = $request->header('id');
        $customer = Customer::where('user_id', $user_id)->get();
        return $customer;
    }

    public function customerById(Request $request)
    {
        $user_id = $request->header('id');
        $id = $request->input('id');
        $customer = Customer::where('user_id', $user_id)->where('id', $id)->first();
        return $customer;
    }

    public function customerUpdate(Request $request)
    {
        $user_id = $request->header('id');
        $id = $request->input('id');
        Customer::where('user_id', $user_id)->where('id', $id)->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Customer Updated Successfully !',
        ]);
    }

    public function customerDelete(Request $request, $id)
    {
        $user_id = $request->header('id');
        Customer::where('user_id', $user_id)->where('id', $id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'customer Deleted Successfully !',
        ]);
    }
}

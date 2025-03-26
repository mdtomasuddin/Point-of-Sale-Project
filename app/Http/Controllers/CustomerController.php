<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Pest\ArchPresets\Custom;

class CustomerController extends Controller
{

    public function customerPage(Request $request)
    {

        $user_id = $request->header('id');
        $customers = Customer::where('user_id', $user_id)->get();
        return Inertia::render('CustomerPage', [
            "customers" => $customers,
        ]);
    }

    public function CustomerSavePage(Request $request)
    {
        $category_id = $request->query('id');
        $user_id = $request->header('id');
        $customer = Customer::where('id', $category_id)->where('user_id', $user_id)->first();
        return Inertia::render('CustomerSavePage', [
            "customer" => $customer,
        ]);
    }

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
        $data = ['message' => "Customer Created Successfully ", 'status' => true, 'error' => ''];
        return redirect('/customer-page')->with($data);
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Customer Created Successfully !',
        // ]);
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

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Customer Updated Successfully !',
        // ]);
        $data = ['message' => "Customer Updated Successfully ", 'status' => true, 'error' => ''];
        return redirect('/customer-page')->with($data);
    }

    public function customerDelete(Request $request, $id)
    {
        $user_id = $request->header('id');
        Customer::where('user_id', $user_id)->where('id', $id)->delete();
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'customer Deleted Successfully !',
        // ]);
        $data = ['message' => "Customer Deleted Successfully ", 'status' => true, 'error' => ''];
        return redirect('/customer-page')->with($data);
    }
}

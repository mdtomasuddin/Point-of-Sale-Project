<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function InvoicePage(Request $request)
    {
        $user_id = $request->header('id');
        $list = Invoice::with('customer', 'InvoiceProduct.product')->where('user_id', $user_id)->get();
        return Inertia::render('InvoiceListPage', ['list' => $list]);
    }

    public function  InvoiceCreate(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            $data = [
                "total" => $request->input('total'),
                "discount" => $request->input('discount'),
                "vat" => $request->input('vat'),
                "payable" => $request->input('payable'),
                "customer_id" => $request->input('customer_id'),
                "user_id" => $user_id
            ];

            $invoice = Invoice::create($data);
            $products = $request->input('products');

            foreach ($products as $product) {
                $existUnit = Product::where('id', $product['id'])->first();
                if (!$existUnit) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => "Product With ID {$product['id']} Not Found",
                    ]);
                }

                if ($existUnit->unit < $product['unit']) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => "Only {$existUnit->unit} unit available stock product for  ID {$product['unit']}",
                    ]);
                }

                InvoiceProduct::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product['id'],
                    'user_id' => $user_id,
                    'qty' => $product['unit'],
                    'sale_price' => $product['price'],
                ]);

                Product::where('id', $product['id'])->update([
                    'unit' => $existUnit->unit - $product['unit'],
                ]);
            }

            DB::commit();
            
            $data = ['message' => 'Product created successfully', 'status' => true, 'error' => ''];
            return redirect('/invoice-page')->with($data);
        } catch (Exception $e) {
            DB::rollBack();
           
            $data = ['message' => 'Product created successfully', 'status' => false, 'error' => $e->getMessage()];
            return redirect()->back()->with($data);
        }
    }

    public function InvoiceList(Request $request)
    {
        $user_id = $request->header('id');
        $invoice = Invoice::with('customer')->where('user_id', $user_id)->get();
        return $invoice;
    }


    public function InvoiceDetails(Request $request)
    {
        $user_id = $request->header('id');
        $customerDetails = Customer::where('user_id', $user_id)->where('id', $request->customer_id)->first();

        $invoiceDetails = Invoice::with('customer')->where('user_id', $user_id)
            ->where('id', $request->invoice_id)->first();

        $invoiceProduct = InvoiceProduct::where('invoice_id', $request->invoice_id)
            ->where('user_id', $user_id)->with('product')->get();

        return [
            'customer' => $customerDetails,
            'invoice' => $invoiceDetails,
            'product' => $invoiceProduct
        ];
    }
    public function InvoiceDelete(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            InvoiceProduct::where('user_id', $user_id)->where('invoice_id', $id)->delete();

            Invoice::where('user_id', $user_id)->where('id', $id)->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => "Invoice Deleted Successfully ",
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);
        }
    }
}

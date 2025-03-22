<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected  $fillable = ['total', 'discount', 'vat', 'payable', 'user_id', 'customer_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customers()
    {
        return $this->belongsTo(Customer::class);
    }
    
    public function InvoiceProducts()
    {
        return $this->hasMany(InvoiceProduct::class);
    }
}

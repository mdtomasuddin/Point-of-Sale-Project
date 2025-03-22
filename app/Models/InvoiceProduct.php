<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    protected  $fillable = ['invoice_id', 'product_id', 'user_id', 'qty', 'sale_price'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    
    public function products()
    {
        return $this->hasOne(Product::class);
    }
}

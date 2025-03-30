<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'unit', 'image', 'user_id', 'category_id', 'brand_id',];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function invoiceProducts()
    {
        return $this->hasMany(InvoiceProduct::class);
    }
}

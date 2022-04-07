<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleProducts extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'box_price',
        'box_weight',
        'box_sale'
    ];

    public function sale()
    {
        return $this->belongsTo(Sales::class, 'sale_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Products::class,  'product_id', 'id');
    }
}

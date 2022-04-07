<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    protected $table = "products";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference',
        'description',
        'unity',
        'price',
        'ipi',
        'status',
        'promotional_price',
        'box_weight',
        'box_price',
        'category'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    public function saleProducts()
    {
        return $this->hasMany(SaleProducts::class, 'product_id', 'id');
    }
}

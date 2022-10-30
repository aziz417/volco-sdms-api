<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyStock extends Model
{
    
    protected $fillable = [
        'warehouse_id',
        'product_id',
        'category_id',
        'brand_id',
        'stock_by',
        'date',
        // 'quantity_box',
        'quantity_pisces',
        // 'booking_quantity_box',
        'booking_quantity_pisces',
        'type',
        
    ];

}

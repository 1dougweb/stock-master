<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MaterialRequestItem extends Model
{
    use HasFactory;

    protected $table = 'material_request_items';

    protected $fillable = [
        'material_request_id',
        'product_id',
        'quantity',
        'price',
    ];

    protected $attributes = [
        'price' => 0,
    ];

    public function materialRequest()
    {
        return $this->belongsTo(MaterialRequest::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

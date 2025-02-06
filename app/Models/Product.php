<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'stock',
        'min_stock',
        'supplier_id',
        'category_id',
        'measurement_unit',
        'unit_label'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'decimal:2',
        'min_stock' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Constantes para as unidades de medida
    const UNIT_TYPES = [
        'unit' => [
            'label' => 'Unidade',
            'unit' => 'un'
        ],
        'weight' => [
            'label' => 'Peso',
            'unit' => 'kg'
        ],
        'length' => [
            'label' => 'Metragem',
            'unit' => 'm'
        ]
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function materialRequestItems()
    {
        return $this->hasMany(MaterialRequestItem::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Acessor para o status do estoque
    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->stock <= $this->min_stock) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    // Escopo para filtrar produtos com estoque baixo
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'min_stock')
            ->where('stock', '>', 0);
    }

    // Escopo para filtrar produtos sem estoque
    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    // Escopo para filtrar por tipo de medida
    public function scopeByMeasurementUnit($query, $unit)
    {
        return $query->where('measurement_unit', $unit);
    }
}

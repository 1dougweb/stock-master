<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class MaterialRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'number',
        'employee_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'description',
        'notes',
        'total_amount',
        'user_id',
        'has_stock_out',
        'has_stock_return',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(MaterialRequestItem::class);
    }
    
    /**
     * Incrementa o estoque com base nos itens da requisição (devolução/cancelamento)
     */
    public function returnItemsToStock()
    {
        $user_id = auth()->id();
        
        DB::beginTransaction();
        try {
            foreach ($this->items as $item) {
                $product = $item->product;
                $previousStock = $product->stock;
                $product->increment('stock', $item->quantity);
                
                // Registrar o movimento de estoque
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $user_id,
                    'material_request_id' => $this->id,
                    'type' => 'entrada',
                    'quantity' => $item->quantity,
                    'previous_stock' => $previousStock,
                    'new_stock' => $product->stock,
                    'notes' => "Devolução de itens da requisição #{$this->number}",
                ]);
            }
            
            // Marcar que houve devolução de estoque
            $this->update(['has_stock_return' => true]);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Decrementa o estoque com base nos itens da requisição (saída)
     */
    public function takeItemsFromStock()
    {
        $user_id = auth()->id();
        
        DB::beginTransaction();
        try {
            foreach ($this->items as $item) {
                $product = $item->product;
                
                // Verificar se há estoque suficiente
                if ($product->stock < $item->quantity) {
                    throw new \Exception("Estoque insuficiente para o produto {$product->name}");
                }
                
                $previousStock = $product->stock;
                $product->decrement('stock', $item->quantity);
                
                // Registrar o movimento de estoque
                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $user_id,
                    'material_request_id' => $this->id,
                    'type' => 'saida',
                    'quantity' => $item->quantity,
                    'previous_stock' => $previousStock,
                    'new_stock' => $product->stock,
                    'notes' => "Saída de itens para requisição #{$this->number}",
                ]);
            }
            
            // Marcar que houve saída de estoque
            $this->update(['has_stock_out' => true]);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

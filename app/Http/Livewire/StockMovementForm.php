<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\StockMovement;
use Livewire\Component;
use Illuminate\Support\Collection;

class StockMovementForm extends Component
{
    public $type = 'entrada';
    public $quantity;
    public $notes;
    public $search = '';
    public $selectedProduct = null;
    public Collection $availableProducts;

    protected $rules = [
        'type' => 'required|in:entrada,saida',
        'quantity' => 'required|numeric|min:0.01',
        'notes' => 'nullable|string',
        'selectedProduct' => 'required|exists:products,id',
    ];

    public function mount()
    {
        $this->availableProducts = collect();
    }

    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            $this->availableProducts = Product::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('sku', 'like', '%' . $this->search . '%')
                ->get();
        } else {
            $this->availableProducts = collect();
        }
    }

    public function selectProduct($productId)
    {
        $this->selectedProduct = $productId;
        $this->search = Product::find($productId)->name;
        $this->availableProducts = collect();
    }

    public function save()
    {
        $this->validate();

        $product = Product::findOrFail($this->selectedProduct);

        if ($this->type === 'saida' && $product->stock < $this->quantity) {
            $this->addError('quantity', 'Quantidade maior que o estoque disponível');
            return;
        }

        $previousStock = $product->stock;
        $newStock = $this->type === 'entrada' 
            ? $previousStock + $this->quantity 
            : $previousStock - $this->quantity;

        StockMovement::create([
            'product_id' => $this->selectedProduct,
            'user_id' => auth()->id(),
            'type' => $this->type,
            'quantity' => $this->quantity,
            'previous_stock' => $previousStock,
            'new_stock' => $newStock,
            'notes' => $this->notes,
        ]);

        $product->update(['stock' => $newStock]);

        $this->emit('movementCreated');
        $this->reset(['type', 'quantity', 'notes', 'search', 'selectedProduct']);
        
        session()->flash('success', 'Movimentação de estoque registrada com sucesso!');
    }

    public function render()
    {
        return view('livewire.stock-movement-form');
    }
}

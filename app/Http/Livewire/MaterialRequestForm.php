<?php

namespace App\Http\Livewire;

use App\Models\MaterialRequest;
use App\Models\Product;
use App\Models\Employee;
use Livewire\Component;

class MaterialRequestForm extends Component
{
    public $materialRequest;
    public $number;
    public $employee_id;
    public $notes;
    public $selectedProducts = [];
    public $employees;
    public $search = '';
    public $searchResults = [];

    protected $rules = [
        'number' => 'required|string|max:20|unique:material_requests,number',
        'employee_id' => 'required|exists:employees,id',
        'notes' => 'nullable|string',
        'selectedProducts' => 'required|array|min:1',
        'selectedProducts.*.id' => 'required|exists:products,id',
        'selectedProducts.*.quantity' => 'required|integer|min:1',
        'selectedProducts.*.price' => 'required|numeric',
    ];

    protected $messages = [
        'number.required' => 'O número da OS é obrigatório.',
        'number.unique' => 'Este número de OS já está em uso.',
        'employee_id.required' => 'É necessário selecionar um funcionário.',
        'employee_id.exists' => 'O funcionário selecionado não é válido.',
        'selectedProducts.required' => 'É necessário adicionar pelo menos um produto.',
        'selectedProducts.min' => 'É necessário adicionar pelo menos um produto.',
        'selectedProducts.*.id.required' => 'É necessário selecionar um produto.',
        'selectedProducts.*.id.exists' => 'O produto selecionado não é válido.',
        'selectedProducts.*.quantity.required' => 'A quantidade é obrigatória.',
        'selectedProducts.*.quantity.integer' => 'A quantidade deve ser um número inteiro.',
        'selectedProducts.*.quantity.min' => 'A quantidade deve ser maior que zero.',
        'selectedProducts.*.price.required' => 'O preço é obrigatório.',
        'selectedProducts.*.price.numeric' => 'O preço deve ser um número.',
    ];

    public function mount($materialRequest = null)
    {
        $this->employees = Employee::orderByName()->get();
        
        if ($materialRequest) {
            $this->materialRequest = $materialRequest;
            $this->number = $materialRequest->number;
            $this->employee_id = $materialRequest->employee_id;
            $this->notes = $materialRequest->notes;
            
            foreach ($materialRequest->items as $item) {
                $this->selectedProducts[] = [
                    'id' => $item->product_id,
                    'name' => $item->product->name,
                    'sku' => $item->product->sku,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            }
        }
    }

    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            $this->searchResults = Product::where(function($query) {
                $query->where('name', 'like', "%{$this->search}%")
                    ->orWhere('sku', 'like', "%{$this->search}%");
            })
            ->take(5)
            ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function selectProduct($productId)
    {
        $product = Product::find($productId);
        
        if ($product) {
            $exists = collect($this->selectedProducts)->contains('id', $productId);
            
            if (!$exists) {
                $this->selectedProducts[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'quantity' => 1,
                    'price' => $product->price ?? 0,
                ];
            }
        }
        
        $this->search = '';
        $this->searchResults = [];
    }

    public function removeProduct($index)
    {
        unset($this->selectedProducts[$index]);
        $this->selectedProducts = array_values($this->selectedProducts);
    }

    public function updateQuantity($index, $quantity)
    {
        if ($quantity > 0) {
            $this->selectedProducts[$index]['quantity'] = $quantity;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->materialRequest) {
            $materialRequest = $this->materialRequest;
            $materialRequest->update([
                'number' => $this->number,
                'employee_id' => $this->employee_id,
                'notes' => $this->notes,
            ]);
            
            $materialRequest->items()->delete();
        } else {
            $materialRequest = MaterialRequest::create([
                'number' => $this->number,
                'employee_id' => $this->employee_id,
                'notes' => $this->notes,
            ]);
        }

        foreach ($this->selectedProducts as $product) {
            $materialRequest->items()->create([
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'] ?? 0,
            ]);
        }

        return redirect()->route('material-requests.show', $materialRequest)
            ->with('success', 'Requisição de material salva com sucesso!');
    }

    public function render()
    {
        return view('livewire.material-request-form');
    }
}

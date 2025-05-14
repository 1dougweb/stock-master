<?php

namespace App\Http\Livewire;

use App\Models\MaterialRequest;
use App\Models\Product;
use App\Models\Employee;
use Livewire\Component;
use App\Http\Controllers\MaterialRequestController;
use Illuminate\Support\Facades\DB;

class MaterialRequestForm extends Component
{
    public $materialRequest;
    public $number;
    public $employee_id;
    public $notes;
    public $take_from_stock = false;
    public $return_to_stock = false;
    public $selectedProducts = [];
    public $employees;
    public $search = '';
    public $searchResults = [];

    protected function rules()
    {
        $numberRule = 'required|string|max:20';
        
        // Se estiver editando, exclui a requisição atual da validação única
        if ($this->materialRequest) {
            $numberRule .= '|unique:material_requests,number,' . $this->materialRequest->id;
        } else {
            $numberRule .= '|unique:material_requests,number';
        }
        
        return [
            'number' => $numberRule,
            'employee_id' => 'required|exists:employees,id',
            'notes' => 'nullable|string',
            'take_from_stock' => 'boolean',
            'return_to_stock' => 'boolean',
            'selectedProducts' => 'required|array|min:1',
            'selectedProducts.*.id' => 'required|exists:products,id',
            'selectedProducts.*.quantity' => 'required|integer|min:1',
            'selectedProducts.*.price' => 'required|numeric',
        ];
    }

    protected $messages = [
        'number.required' => 'O número da OS é obrigatório.',
        'number.unique' => 'Este número de OS já está em uso.',
        'employee_id.required' => 'É necessário selecionar um funcionário.',
        'employee_id.exists' => 'O funcionário selecionado não é válido.',
        'take_from_stock.boolean' => 'O campo "Retirar do estoque" deve ser um booleano.',
        'return_to_stock.boolean' => 'O campo "Devolver ao estoque" deve ser um booleano.',
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

    /**
     * Exibe uma notificação para o usuário
     *
     * @param string $message
     * @param string $type
     * @return void
     */
    private function notify($message, $type = 'success')
    {
        // Compatibilidade com Livewire 3.x
        $this->dispatch('notification', [
            'message' => $message,
            'type' => $type
        ]);
        
        // Também armazenar na sessão para quando houver redirecionamento
        session()->flash($type, $message);
    }

    public function save()
    {
        $this->validate();

        // Verificar ações conflitantes
        if ($this->take_from_stock && $this->return_to_stock) {
            $this->notify('Não é possível retirar e devolver itens simultaneamente.', 'error');
            return null;
        }

        // Computar o valor total com base nos produtos selecionados
        $totalAmount = collect($this->selectedProducts)->sum(function ($product) {
            return $product['quantity'] * $product['price'];
        });

        DB::beginTransaction();
        try {
            if ($this->materialRequest) {
                $materialRequest = $this->materialRequest;
                $materialRequest->update([
                    'number' => $this->number,
                    'employee_id' => $this->employee_id,
                    'notes' => $this->notes,
                    'total_amount' => $totalAmount,
                ]);
                
                // Limpar itens existentes para inserir os novos
                $materialRequest->items()->delete();
                
                $successMessage = 'Requisição de material atualizada com sucesso!';
            } else {
                $materialRequest = MaterialRequest::create([
                    'number' => $this->number,
                    'employee_id' => $this->employee_id,
                    'notes' => $this->notes,
                    'total_amount' => $totalAmount,
                    'user_id' => auth()->id(), // Adicionar user_id para rastreabilidade
                ]);
                
                $successMessage = 'Requisição de material criada com sucesso!';
            }

            // Criar os novos itens da requisição
            foreach ($this->selectedProducts as $product) {
                $materialRequest->items()->create([
                    'product_id' => $product['id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'] ?? 0,
                ]);
            }

            // Processar as ações de estoque
            try {
                $controller = new MaterialRequestController();
                
                if ($this->take_from_stock) {
                    $controller->processLivewireUpdate($materialRequest, 'take_from_stock');
                    $successMessage .= ' Os itens foram retirados do estoque.';
                } else if ($this->return_to_stock) {
                    $controller->processLivewireUpdate($materialRequest, 'return_to_stock');
                    $successMessage .= ' Os itens foram devolvidos ao estoque.';
                }
            } catch (\Exception $e) {
                DB::rollBack();
                $this->notify($e->getMessage(), 'error');
                return null;
            }

            DB::commit();
            
            // Notificar o usuário antes do redirecionamento
            $this->notify($successMessage, 'success');
            
            // Redirecionar de volta para a requisição
            return redirect()->route('material-requests.show', $materialRequest);
                
        } catch (\Exception $e) {
            DB::rollBack();
            $this->notify($e->getMessage(), 'error');
            return null;
        }
    }

    public function render()
    {
        return view('livewire.material-request-form');
    }
}

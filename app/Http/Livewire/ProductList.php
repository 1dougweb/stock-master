<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $search = '';
    public $category = '';
    public $supplier = '';
    public $lowStock = false;
    public $outOfStock = false;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $productToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'supplier' => ['except' => ''],
        'lowStock' => ['except' => false],
        'outOfStock' => ['except' => false],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleStockFilter($type)
    {
        switch ($type) {
            case 'all':
                $this->lowStock = false;
                $this->outOfStock = false;
                break;
            case 'low':
                $this->lowStock = true;
                $this->outOfStock = false;
                break;
            case 'out':
                $this->lowStock = false;
                $this->outOfStock = true;
                break;
            case 'both':
                $this->lowStock = true;
                $this->outOfStock = true;
                break;
        }
        $this->resetPage();
    }

    public function doSearch()
    {
        $this->search = $this->searchTerm;
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingSupplier()
    {
        $this->resetPage();
    }

    public function confirmDelete($productId)
    {
        $this->productToDelete = $productId;
    }

    public function cancelDelete()
    {
        $this->productToDelete = null;
    }

    public function delete()
    {
        $product = Product::find($this->productToDelete);
        if ($product) {
            $product->delete();
        }
        $this->productToDelete = null;
        session()->flash('success', 'Produto excluído com sucesso.');
    }

    public function edit($productId)
    {
        return redirect()->route('products.edit', $productId);
    }

    public function clearSearch()
    {
        $this->searchTerm = '';
        $this->search = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query()
            ->with(['category', 'supplier'])
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%')
                        ->orWhere('sku', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->category, function ($query) {
                $query->where('category_id', $this->category);
            })
            ->when($this->supplier, function ($query) {
                $query->where('supplier_id', $this->supplier);
            });

        // Filtros de estoque
        if ($this->lowStock && !$this->outOfStock) {
            $query->lowStock();
        } elseif ($this->outOfStock && !$this->lowStock) {
            $query->outOfStock();
        } elseif ($this->lowStock && $this->outOfStock) {
            $query->where(function ($query) {
                $query->lowStock()
                    ->orWhere(function ($query) {
                        $query->outOfStock();
                    });
            });
        }

        // Ordenação
        $query->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.product-list', [
            'products' => $query->paginate(10),
            'categories' => Category::orderBy('name')->get(),
            'suppliers' => Supplier::orderBy('company_name')->get(),
        ]);
    }
}

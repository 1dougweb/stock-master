<?php

namespace App\Http\Livewire;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class SupplierList extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $search = '';
    public $perPage = 10;
    public $supplierToDelete = null;

    public function confirmDelete($supplierId)
    {
        $this->supplierToDelete = Supplier::find($supplierId);
    }

    public function cancelDelete()
    {
        $this->supplierToDelete = null;
    }

    public function delete()
    {
        if ($this->supplierToDelete) {
            if ($this->supplierToDelete->products()->exists()) {
                session()->flash('error', 'Não é possível excluir um fornecedor que possui produtos vinculados.');
            } else {
                try {
                    $this->supplierToDelete->delete();
                    session()->flash('success', 'Fornecedor excluído com sucesso!');
                } catch (\Exception $e) {
                    session()->flash('error', 'Erro ao excluir fornecedor: ' . $e->getMessage());
                }
            }
            $this->supplierToDelete = null;
        }
    }

    public function doSearch()
    {
        $this->search = $this->searchTerm;
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->searchTerm = '';
        $this->search = '';
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function render()
    {
        $suppliers = Supplier::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('company_name', 'like', '%' . $this->search . '%')
                        ->orWhere('trading_name', 'like', '%' . $this->search . '%')
                        ->orWhere('cnpj', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('company_name')
            ->paginate($this->perPage);

        return view('livewire.supplier-list', [
            'suppliers' => $suppliers
        ]);
    }
}

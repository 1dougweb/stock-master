<?php

namespace App\Http\Livewire;

use App\Models\MaterialRequest;
use Livewire\Component;
use Livewire\WithPagination;

class MaterialRequestList extends Component
{
    use WithPagination;

    public $search = '';
    public $stockFilter = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'stockFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $requests = MaterialRequest::query()
            ->with(['items.product'])
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('number', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->stockFilter === 'processed', function ($query) {
                $query->where('has_stock_out', true);
            })
            ->when($this->stockFilter === 'pending', function ($query) {
                $query->where('has_stock_out', false);
            })
            ->when($this->stockFilter === 'returned', function ($query) {
                $query->where('has_stock_return', true);
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.material-request-list', [
            'requests' => $requests,
        ]);
    }
}

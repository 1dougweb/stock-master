<?php

namespace App\Http\Livewire;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeList extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $search = '';
    public $perPage = 10;

    public function doSearch()
    {
        $this->search = $this->searchTerm;
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->searchTerm = '';
        $this->search = '';
        $this->resetPage();
    }

    public function render()
    {
        $employees = Employee::query()
            ->with('user')
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhere('position', 'like', '%' . $this->search . '%')
                ->orWhere('department', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id')
            ->paginate($this->perPage);

        return view('livewire.employee-list', [
            'employees' => $employees
        ]);
    }
}

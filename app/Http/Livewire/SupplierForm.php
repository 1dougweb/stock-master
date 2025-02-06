<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Supplier;

class SupplierForm extends Component
{
    public $supplier;
    public $cnpj;
    public $company_name;
    public $trading_name;
    public $address;
    public $city;
    public $state;
    public $zip_code;
    public $phone;
    public $whatsapp;
    public $email;
    public $contact_person;

    protected $rules = [
        'cnpj' => 'required|string|min:14|max:18',
        'company_name' => 'required|string|max:255',
        'trading_name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'state' => 'required|string|size:2',
        'zip_code' => 'required|string|min:8|max:9',
        'phone' => 'required|string|max:20',
        'whatsapp' => 'nullable|string|max:20',
        'email' => 'required|email|max:255',
        'contact_person' => 'required|string|max:255',
    ];

    protected $messages = [
        'cnpj.required' => 'O CNPJ é obrigatório',
        'cnpj.min' => 'O CNPJ deve ter no mínimo 14 dígitos',
        'cnpj.max' => 'O CNPJ deve ter no máximo 18 caracteres',
        'company_name.required' => 'A razão social é obrigatória',
        'company_name.max' => 'A razão social deve ter no máximo 255 caracteres',
        'trading_name.required' => 'O nome fantasia é obrigatório',
        'trading_name.max' => 'O nome fantasia deve ter no máximo 255 caracteres',
        'address.required' => 'O endereço é obrigatório',
        'address.max' => 'O endereço deve ter no máximo 255 caracteres',
        'city.required' => 'A cidade é obrigatória',
        'city.max' => 'A cidade deve ter no máximo 100 caracteres',
        'state.required' => 'O estado é obrigatório',
        'state.size' => 'O estado deve ter 2 caracteres',
        'zip_code.required' => 'O CEP é obrigatório',
        'zip_code.min' => 'O CEP deve ter no mínimo 8 dígitos',
        'zip_code.max' => 'O CEP deve ter no máximo 9 caracteres',
        'phone.required' => 'O telefone é obrigatório',
        'phone.max' => 'O telefone deve ter no máximo 20 caracteres',
        'whatsapp.max' => 'O WhatsApp deve ter no máximo 20 caracteres',
        'email.required' => 'O e-mail é obrigatório',
        'email.email' => 'Digite um e-mail válido',
        'email.max' => 'O e-mail deve ter no máximo 255 caracteres',
        'contact_person.required' => 'A pessoa de contato é obrigatória',
        'contact_person.max' => 'A pessoa de contato deve ter no máximo 255 caracteres',
    ];

    public function mount($supplier = null)
    {
        if ($supplier) {
            $this->supplier = $supplier;
            $this->cnpj = $supplier->cnpj;
            $this->company_name = $supplier->company_name;
            $this->trading_name = $supplier->trading_name;
            $this->address = $supplier->address;
            $this->city = $supplier->city;
            $this->state = $supplier->state;
            $this->zip_code = $supplier->zip_code;
            $this->phone = $supplier->phone;
            $this->whatsapp = $supplier->whatsapp;
            $this->email = $supplier->email;
            $this->contact_person = $supplier->contact_person;
        }
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'cnpj' => $this->cnpj,
                'company_name' => $this->company_name,
                'trading_name' => $this->trading_name,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'zip_code' => $this->zip_code,
                'phone' => $this->phone,
                'whatsapp' => $this->whatsapp,
                'email' => $this->email,
                'contact_person' => $this->contact_person,
            ];

            if ($this->supplier) {
                $this->supplier->update($data);
                session()->flash('success', 'Fornecedor atualizado com sucesso!');
            } else {
                Supplier::create($data);
                session()->flash('success', 'Fornecedor cadastrado com sucesso!');
            }

            return redirect()->route('suppliers.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar o fornecedor: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.supplier-form');
    }
}

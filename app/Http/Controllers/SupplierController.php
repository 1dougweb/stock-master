<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('products')
            ->orderBy('company_name')
            ->paginate(10);

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cnpj' => 'required|string|size:14|unique:suppliers,cnpj',
            'company_name' => 'required|string|max:255',
            'trading_name' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|size:2',
            'zip_code' => 'required|string|size:8',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
        ]);

        try {
            Supplier::create($validated);
            return redirect()->route('suppliers.index')->with('success', 'Fornecedor criado com sucesso!');
        } catch (\Exception $e) {
            throw ValidationException::withMessages(['error' => 'Erro ao criar fornecedor. ' . $e->getMessage()]);
        }
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['products' => function ($query) {
            $query->latest()->limit(10);
        }]);

        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'cnpj' => 'required|string|size:14|unique:suppliers,cnpj,' . $supplier->id,
            'company_name' => 'required|string|max:255',
            'trading_name' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|size:2',
            'zip_code' => 'required|string|size:8',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
        ]);

        try {
            $supplier->update($validated);
            return redirect()->route('suppliers.index')->with('success', 'Fornecedor atualizado com sucesso!');
        } catch (\Exception $e) {
            throw ValidationException::withMessages(['error' => 'Erro ao atualizar fornecedor. ' . $e->getMessage()]);
        }
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->products()->exists()) {
            return back()->with('error', 'Não é possível excluir um fornecedor que possui produtos.');
        }

        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Fornecedor excluído com sucesso!');
    }

    public function fetchCNPJ(Request $request)
    {
        $request->validate([
            'cnpj' => 'required|string|size:14',
        ]);

        try {
            $response = Http::get(config('services.cnpj.url') . '/' . $request->cnpj);
            
            if ($response->successful()) {
                $data = $response->json();
                
                return response()->json([
                    'company_name' => $data['razao_social'] ?? null,
                    'trading_name' => $data['nome_fantasia'] ?? null,
                    'address' => $data['logradouro'] ?? null,
                    'city' => $data['municipio'] ?? null,
                    'state' => $data['uf'] ?? null,
                    'zip_code' => preg_replace('/[^0-9]/', '', $data['cep'] ?? ''),
                    'phone' => $data['telefone'] ?? null,
                    'email' => $data['email'] ?? null,
                ]);
            }

            return response()->json(['error' => 'CNPJ não encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao consultar CNPJ'], 500);
        }
    }
}

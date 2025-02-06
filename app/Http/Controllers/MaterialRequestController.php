<?php

namespace App\Http\Controllers;

use App\Models\MaterialRequest;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

class MaterialRequestController extends Controller
{
    public function index()
    {
        $materialRequests = MaterialRequest::with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('material-requests.index', compact('materialRequests'));
    }

    public function create()
    {
        return view('material-requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|string|max:255|unique:material_requests,number',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'description' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|string|in:pendente,em_andamento,concluida,cancelada',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $materialRequest = MaterialRequest::create([
                'number' => $validated['number'],
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'],
                'description' => $validated['description'],
                'notes' => $validated['notes'],
                'status' => $validated['status'],
                'user_id' => auth()->id(),
                'total_amount' => collect($validated['items'])->sum(function ($item) {
                    return $item['price'] * $item['quantity'];
                }),
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                if ($product->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'error' => "Estoque insuficiente para o produto {$product->name}"
                    ]);
                }

                $materialRequest->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();
            return redirect()->route('material-requests.index')
                ->with('success', 'Requisição de material criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(MaterialRequest $materialRequest)
    {
        $materialRequest->load('items.product');
        return view('material-requests.show', compact('materialRequest'));
    }

    public function edit(MaterialRequest $materialRequest)
    {
        return view('material-requests.edit', compact('materialRequest'));
    }

    public function update(Request $request, MaterialRequest $materialRequest)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'description' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|string|in:pendente,em_andamento,concluida,cancelada',
        ]);

        $materialRequest->update($validated);

        return redirect()->route('material-requests.index')
            ->with('success', 'Requisição de material atualizada com sucesso!');
    }

    public function destroy(MaterialRequest $materialRequest)
    {
        DB::beginTransaction();
        try {
            // Restaurar o estoque dos produtos
            foreach ($materialRequest->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            $materialRequest->delete();
            DB::commit();

            return redirect()->route('material-requests.index')
                ->with('success', 'Requisição de material excluída com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao excluir requisição de material.');
        }
    }

    public function generatePDF(MaterialRequest $materialRequest)
    {
        $materialRequest->load('items.product');
        
        $pdf = PDF::loadView('material-requests.pdf', compact('materialRequest'));
        
        return $pdf->stream("requisicao-{$materialRequest->number}.pdf");
    }

    public function complete(MaterialRequest $materialRequest)
    {
        $materialRequest->update(['status' => 'concluida']);
        
        return back()->with('success', 'Requisição concluída com sucesso!');
    }
}

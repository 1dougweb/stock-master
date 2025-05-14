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
            'take_from_stock' => 'sometimes|boolean',
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
                'user_id' => auth()->id(),
                'has_stock_out' => false,
                'has_stock_return' => false,
                'total_amount' => collect($validated['items'])->sum(function ($item) {
                    return $item['price'] * $item['quantity'];
                }),
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                if ($validated['take_from_stock'] ?? false) {
                    if ($product->stock < $item['quantity']) {
                        throw ValidationException::withMessages([
                            'error' => "Estoque insuficiente para o produto {$product->name}"
                        ]);
                    }
                }

                $materialRequest->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            // Registrar movimento de estoque se solicitado
            if ($validated['take_from_stock'] ?? false) {
                $materialRequest->takeItemsFromStock();
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
            'take_from_stock' => 'sometimes|boolean',
            'return_to_stock' => 'sometimes|boolean',
        ]);

        // Verificar ações contraditórias
        if (($validated['take_from_stock'] ?? false) && ($validated['return_to_stock'] ?? false)) {
            return back()->with('error', 'Não é possível realizar saída e devolução de estoque simultaneamente.');
        }

        DB::beginTransaction();
        try {
            // Atualizar a requisição
            $materialRequest->update([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'],
                'description' => $validated['description'],
                'notes' => $validated['notes'],
            ]);

            // Processar ações de estoque
            if ($validated['take_from_stock'] ?? false) {
                $materialRequest->takeItemsFromStock();
            } else if ($validated['return_to_stock'] ?? false) {
                $materialRequest->returnItemsToStock();
            }

            DB::commit();
            return redirect()->route('material-requests.show', $materialRequest)
                ->with('success', 'Requisição de material atualizada com sucesso!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(MaterialRequest $materialRequest)
    {
        DB::beginTransaction();
        try {
            // Restaurar o estoque dos produtos se já houve saída
            if ($materialRequest->has_stock_out && !$materialRequest->has_stock_return) {
                foreach ($materialRequest->items as $item) {
                    $item->product->increment('stock', $item->quantity);
                }
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
        DB::beginTransaction();
        try {
            // Verificar se já houve saída de estoque
            if ($materialRequest->has_stock_out) {
                return back()->with('error', 'Esta requisição já teve saída de estoque processada.');
            }
            
            // Verificar se há estoque suficiente para todos os itens
            foreach ($materialRequest->items as $item) {
                if ($item->product->stock < $item->quantity) {
                    throw ValidationException::withMessages([
                        'error' => "Estoque insuficiente para o produto {$item->product->name}"
                    ]);
                }
            }
            
            // Realizar a saída de estoque
            $materialRequest->takeItemsFromStock();
            
            DB::commit();
            return back()->with('success', 'Itens retirados do estoque com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function processLivewireUpdate(MaterialRequest $materialRequest, $action)
    {
        try {
            if ($action === 'take_from_stock') {
                $materialRequest->takeItemsFromStock();
            } elseif ($action === 'return_to_stock') {
                $materialRequest->returnItemsToStock();
            }
            
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

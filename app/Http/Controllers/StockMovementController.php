<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stockMovements = StockMovement::with(['product', 'user'])
            ->latest()
            ->paginate(15);

        return view('stock-movements.index', compact('stockMovements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('stock-movements.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:entrada,saida',
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($validated['product_id']);
            
            // Check if there's enough stock for outgoing movement
            if ($validated['type'] === 'saida' && $product->stock < $validated['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => "Estoque insuficiente. Disponível: {$product->stock} {$product->unit_label}"
                ]);
            }
            
            $previousStock = $product->stock;
            
            // Update product stock
            if ($validated['type'] === 'entrada') {
                $product->increment('stock', $validated['quantity']);
            } else {
                $product->decrement('stock', $validated['quantity']);
            }
            
            // Create stock movement record
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'previous_stock' => $previousStock,
                'new_stock' => $product->stock,
                'notes' => $validated['notes'] ?? 'Movimento de estoque manual',
            ]);
            
            DB::commit();
            return redirect()->route('stock-movements.index')
                ->with('success', 'Movimento de estoque registrado com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StockMovement $stockMovement)
    {
        return view('stock-movements.show', compact('stockMovement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockMovement $stockMovement)
    {
        // Stock movements should not be editable after creation
        // as they represent a historical record
        return redirect()->route('stock-movements.index')
            ->with('error', 'Movimentos de estoque não podem ser editados após criação.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockMovement $stockMovement)
    {
        // Block updates for integrity reasons
        return redirect()->route('stock-movements.index')
            ->with('error', 'Movimentos de estoque não podem ser atualizados após criação.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockMovement $stockMovement)
    {
        // For audit and integrity reasons, we should only soft delete
        $stockMovement->delete();
        return redirect()->route('stock-movements.index')
            ->with('success', 'Registro de movimento removido com sucesso.');
    }
}

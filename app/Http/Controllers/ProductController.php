<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        // Este método não é usado já que estamos usando Livewire
        return redirect()->route('products.index');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // Este método não é usado já que estamos usando Livewire
        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produto excluído com sucesso.');
    }

    public function stockHistory(Product $product)
    {
        $movements = $product->stockMovements()
            ->with(['user', 'materialRequest'])
            ->latest()
            ->paginate(10);

        return view('products.stock-history', compact('product', 'movements'));
    }

    
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        return view('categories.index');
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'sku_prefix' => 'required|size:3|unique:categories,sku_prefix|alpha_num',
        ]);

        Category::create($validated);

        session()->flash('success', 'Categoria criada com sucesso.');
        return redirect()->route('categories.index');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'sku_prefix' => [
                'required',
                'size:3',
                'alpha_num',
                Rule::unique('categories', 'sku_prefix')->ignore($category->id),
            ],
        ]);

        $category->update($validated);

        session()->flash('success', 'Categoria atualizada com sucesso.');
        return redirect()->route('categories.index');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            session()->flash('error', 'Não é possível excluir uma categoria que possui produtos.');
            return redirect()->route('categories.index');
        }

        $category->delete();
        session()->flash('success', 'Categoria excluída com sucesso.');
        return redirect()->route('categories.index');
    }
}

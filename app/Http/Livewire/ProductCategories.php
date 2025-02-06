<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class ProductCategories extends Component
{
    public $product;
    public $categories = [];
    public $productCategories = [];
    public $newCategory = '';

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->categories = Category::orderBy('name')->get();
        $this->productCategories = $product->categories->pluck('id')->toArray();
    }

    public function addCategory()
    {
        if (!empty($this->newCategory)) {
            $category = Category::create(['name' => $this->newCategory]);
            $this->categories = Category::orderBy('name')->get();
            $this->productCategories[] = $category->id;
            $this->newCategory = '';
        }
    }

    public function removeCategory($categoryId)
    {
        $this->productCategories = array_filter($this->productCategories, function($id) use ($categoryId) {
            return $id != $categoryId;
        });
    }

    public function saveCategories()
    {
        $this->product->categories()->sync($this->productCategories);
        session()->flash('success', 'Categorias atualizadas com sucesso.');
    }

    public function render()
    {
        return view('livewire.product-categories');
    }
}

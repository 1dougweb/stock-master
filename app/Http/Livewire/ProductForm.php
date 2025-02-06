<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Livewire\Component;

class ProductForm extends Component
{
    public $product;
    public $name;
    public $description;
    public $price;
    public $stock;
    public $min_stock;
    public $category_id;
    public $supplier_id;
    public $measurement_unit = 'unit';
    public $unit_label;
    
    public $categories;
    public $suppliers;
    public $unitTypes;

    protected $rules = [
        'name' => 'required|max:255',
        'description' => 'nullable',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|numeric|min:0',
        'min_stock' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'required|exists:suppliers,id',
        'measurement_unit' => 'required|in:unit,weight,length'
    ];

    public function mount($product = null)
    {
        $this->product = $product;
        
        if ($product) {
            $this->name = $product->name;
            $this->description = $product->description;
            $this->price = $product->price;
            $this->stock = $product->stock;
            $this->min_stock = $product->min_stock;
            $this->category_id = $product->category_id;
            $this->supplier_id = $product->supplier_id;
            $this->measurement_unit = $product->measurement_unit;
            $this->unit_label = $product->unit_label;
        } else {
            $this->unit_label = Product::UNIT_TYPES[$this->measurement_unit]['unit'];
        }

        $this->categories = Category::orderBy('name')->get();
        $this->suppliers = Supplier::orderBy('company_name')->get();
        $this->unitTypes = Product::UNIT_TYPES;
    }

    public function updatedMeasurementUnit($value)
    {
        // Define a unidade padrão baseada no tipo selecionado
        $this->unit_label = Product::UNIT_TYPES[$value]['unit'];
    }

    private function generateSKU($name)
    {
        // Remove acentos e caracteres especiais
        $name = preg_replace('/[^A-Za-z0-9]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $name));
        
        // Converte para maiúsculas e pega os primeiros 3 caracteres
        $prefix = strtoupper(substr($name, 0, 3));
        
        // Se não tiver 3 caracteres, completa com X
        $prefix = str_pad($prefix, 3, 'X');
        
        // Gera um número sequencial de 4 dígitos
        $lastProduct = Product::orderBy('id', 'desc')->first();
        $sequence = $lastProduct ? str_pad(($lastProduct->id + 1), 4, '0', STR_PAD_LEFT) : '0001';
        
        // Combina para formar o SKU
        $sku = $prefix . $sequence;
        
        // Verifica se o SKU já existe e adiciona um sufixo se necessário
        $counter = 1;
        $originalSku = $sku;
        while (Product::where('sku', $sku)->exists()) {
            $sku = $originalSku . chr(64 + $counter); // Adiciona A, B, C, etc.
            $counter++;
        }
        
        return $sku;
    }

    public function save()
    {
        $validatedData = $this->validate();
        
        // Define a unidade padrão baseada no tipo selecionado
        $validatedData['unit_label'] = Product::UNIT_TYPES[$validatedData['measurement_unit']]['unit'];
        
        if ($this->product) {
            // Atualização
            $this->product->update($validatedData);
            session()->flash('success', 'Produto atualizado com sucesso.');
        } else {
            // Criação
            // Gera o SKU automaticamente apenas para novos produtos
            $validatedData['sku'] = $this->generateSKU($validatedData['name']);
            Product::create($validatedData);
            session()->flash('success', 'Produto criado com sucesso.');
        }
        
        return redirect()->route('products.index');
    }

    public function render()
    {
        return view('livewire.product-form');
    }
}

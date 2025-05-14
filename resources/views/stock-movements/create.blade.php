<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova Movimentação de Estoque') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <form action="{{ route('stock-movements.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Produto -->
                            <div class="col-span-2">
                                <x-label for="product_id" value="{{ __('Produto') }}" />
                                <x-select id="product_id" name="product_id" class="mt-1 block w-full" required>
                                    <option value="">Selecione um produto</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-stock="{{ $product->stock }}" data-unit="{{ $product->unit_label }}">
                                            {{ $product->name }} ({{ $product->sku }}) - Estoque: {{ $product->stock }} {{ $product->unit_label }}
                                        </option>
                                    @endforeach
                                </x-select>
                                <x-input-error for="product_id" class="mt-2" />
                            </div>

                            <!-- Tipo de Movimento -->
                            <div>
                                <x-label for="type" value="{{ __('Tipo de Movimento') }}" />
                                <x-select id="type" name="type" class="mt-1 block w-full" required>
                                    <option value="entrada">Entrada</option>
                                    <option value="saida">Saída</option>
                                </x-select>
                                <x-input-error for="type" class="mt-2" />
                            </div>

                            <!-- Quantidade -->
                            <div>
                                <x-label for="quantity" value="{{ __('Quantidade') }}" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <x-input id="quantity" type="number" step="0.01" min="0.01" name="quantity" class="block w-full" required />
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm" id="unit-label">un</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-sm text-gray-500" id="stock-info">Estoque atual: -</p>
                                <x-input-error for="quantity" class="mt-2" />
                            </div>

                            <!-- Observações -->
                            <div class="col-span-2">
                                <x-label for="notes" value="{{ __('Observações') }}" />
                                <x-textarea id="notes" name="notes" class="mt-1 block w-full" />
                                <x-input-error for="notes" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('stock-movements.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                                {{ __('Cancelar') }}
                            </a>
                            <x-button>
                                {{ __('Salvar') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productSelect = document.getElementById('product_id');
            const typeSelect = document.getElementById('type');
            const quantityInput = document.getElementById('quantity');
            const unitLabel = document.getElementById('unit-label');
            const stockInfo = document.getElementById('stock-info');
            
            function updateProductInfo() {
                if (productSelect.selectedIndex > 0) {
                    const option = productSelect.options[productSelect.selectedIndex];
                    const stock = option.getAttribute('data-stock');
                    const unit = option.getAttribute('data-unit');
                    
                    unitLabel.textContent = unit;
                    stockInfo.textContent = `Estoque atual: ${stock} ${unit}`;
                    
                    // Verificar se é saída e o estoque é insuficiente
                    if (typeSelect.value === 'saida') {
                        quantityInput.max = stock;
                        
                        if (parseFloat(quantityInput.value) > parseFloat(stock)) {
                            stockInfo.classList.add('text-red-500');
                            stockInfo.classList.remove('text-gray-500');
                        } else {
                            stockInfo.classList.remove('text-red-500');
                            stockInfo.classList.add('text-gray-500');
                        }
                    } else {
                        quantityInput.removeAttribute('max');
                        stockInfo.classList.remove('text-red-500');
                        stockInfo.classList.add('text-gray-500');
                    }
                } else {
                    unitLabel.textContent = 'un';
                    stockInfo.textContent = 'Estoque atual: -';
                    stockInfo.classList.remove('text-red-500');
                    stockInfo.classList.add('text-gray-500');
                }
            }
            
            productSelect.addEventListener('change', updateProductInfo);
            typeSelect.addEventListener('change', updateProductInfo);
            quantityInput.addEventListener('input', updateProductInfo);
            
            // Inicializar
            updateProductInfo();
        });
    </script>
</x-app-layout> 
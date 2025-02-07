<div>
    <form wire:submit.prevent="save" class="space-y-6">
        <div>
            <x-label for="name" value="{{ __('Nome') }}" />
            <x-input id="name" class="block mt-1 w-full" type="text" wire:model="name" required autofocus />
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-label for="description" value="{{ __('Descrição') }}" />
            <textarea id="description" wire:model="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="3"></textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-label for="measurement_unit" value="{{ __('Tipo de Produto') }}" />
            <select id="measurement_unit" wire:model="measurement_unit" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                @foreach($unitTypes as $value => $type)
                    <option value="{{ $value }}">{{ $type['label'] }}</option>
                @endforeach
            </select>
            @error('measurement_unit')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <x-label for="price" value="{{ __('Preço') }}" />
                <x-input id="price" class="block mt-1 w-full" type="number" wire:model="price" step="0.01" required />
                @error('price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            @if($measurement_unit === 'unit')
            <div>
                <x-label for="stock" value="{{ __('Quantidade em Estoque') }}" />
                <div class="flex items-center">
                    <x-input id="stock" class="block mt-1 w-full" type="number" wire:model="stock" step="1" min="0" required />
                    <span class="ml-2 text-gray-600">UN</span>
                </div>
                @error('stock')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-label for="min_stock" value="{{ __('Quantidade Mínima') }}" />
                <div class="flex items-center">
                    <x-input id="min_stock" class="block mt-1 w-full" type="number" wire:model="min_stock" step="1" min="0" required />
                    <span class="ml-2 text-gray-600">UN</span>
                </div>
                @error('min_stock')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            @if($measurement_unit === 'weight')
            <div>
                <x-label for="stock" value="{{ __('Peso em Estoque') }}" />
                <div class="flex items-center">
                    <x-input id="stock" class="block mt-1 w-full" type="number" wire:model="stock" step="1" min="0" required />
                    <span class="ml-2 text-gray-600">KG</span>
                </div>
                @error('stock')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-label for="min_stock" value="{{ __('Peso Mínimo') }}" />
                <div class="flex items-center">
                    <x-input id="min_stock" class="block mt-1 w-full" type="number" wire:model="min_stock" step="1" min="0" required />
                    <span class="ml-2 text-gray-600">KG</span>
                </div>
                @error('min_stock')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            @if($measurement_unit === 'length')
            <div>
                <x-label for="stock" value="{{ __('Metragem em Estoque') }}" />
                <div class="flex items-center">
                    <x-input id="stock" class="block mt-1 w-full" type="number" wire:model="stock" step="1" min="0" required />
                    <span class="ml-2 text-gray-600">M</span>
                </div>
                @error('stock')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-label for="min_stock" value="{{ __('Metragem Mínima') }}" />
                <div class="flex items-center">
                    <x-input id="min_stock" class="block mt-1 w-full" type="number" wire:model="min_stock" step="1" min="0" required />
                    <span class="ml-2 text-gray-600">M</span>
                </div>
                @error('min_stock')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-label for="category_id" value="{{ __('Categoria') }}" />
                <select id="category_id" wire:model="category_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                    <option value="">Selecione uma categoria</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-label for="supplier_id" value="{{ __('Fornecedor') }}" />
                <select id="supplier_id" wire:model="supplier_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                    <option value="">Selecione um fornecedor</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->company_name }}</option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-button>
                {{ __('Salvar') }}
            </x-button>
        </div>
    </form>
</div>

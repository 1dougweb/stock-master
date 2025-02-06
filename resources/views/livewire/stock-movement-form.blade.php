<div>
    <form wire:submit.prevent="save">
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tipo de Movimentação</label>
                <div class="mt-2 space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model="type" value="entrada" class="form-radio text-indigo-600">
                        <span class="ml-2">Entrada</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model="type" value="saida" class="form-radio text-indigo-600">
                        <span class="ml-2">Saída</span>
                    </label>
                </div>
                @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Produto</label>
                <div class="mt-1 relative">
                    <input type="text" wire:model="search" placeholder="Buscar produtos..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    
                    @if($availableProducts->isNotEmpty())
                        <div class="absolute z-10 w-full bg-white mt-1 rounded-md shadow-lg">
                            <ul class="max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                @foreach($availableProducts as $product)
                                    <li wire:click="selectProduct({{ $product->id }})" class="cursor-pointer hover:bg-gray-100 px-4 py-2">
                                        {{ $product->name }} (Estoque: {{ $product->stock }})
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                @error('selectedProduct') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantidade</label>
                <input type="number" id="quantity" wire:model="quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" min="0.01" step="0.01">
                @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700">Observações</label>
                <textarea id="notes" wire:model="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Registrar Movimentação
                </button>
            </div>
        </div>
    </form>
</div>

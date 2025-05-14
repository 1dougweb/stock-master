<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova Requisição de Material') }}
        </h2>
    </x-slot>
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Número -->
                            <div class="col-span-2">
                                <x-label for="number" value="{{ __('Número OS') }}" />
                                <div class="mt-1 flex rounded-md max-w-lg">
                                    <span class="inline-flex items-center px-4 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-base font-medium">
                                        OS
                                    </span>
                                    <x-input id="number" type="text" class="rounded-none rounded-r-md text-lg" wire:model="number" placeholder="Digite o número da OS" />
                                </div>
                                <x-input-error for="number" class="mt-2" />
                            </div>

                            <!-- Funcionário Requisitante -->
                            <div class="col-span-2">
                                <x-label for="employee_id" value="{{ __('Funcionário Requisitante') }}" />
                                <x-select id="employee_id" class="mt-1 block w-full" wire:model="employee_id">
                                    <option value="">Selecione um funcionário</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->user->name }} - {{ $employee->position }}</option>
                                    @endforeach
                                </x-select>
                                <x-input-error for="employee_id" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div class="col-span-2">
                                <x-label value="{{ __('Ações de Estoque') }}" />
                                <div class="mt-2 space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" class="form-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            wire:model="take_from_stock" />
                                        <span class="ml-2">Retirar itens do estoque</span>
                                    </label>
                                    
                                    <label class="inline-flex items-center ml-6">
                                        <input type="checkbox" class="form-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            wire:model="return_to_stock" />
                                        <span class="ml-2">Devolver itens ao estoque</span>
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Selecione uma ação caso deseje atualizar o estoque agora.</p>
                                <x-input-error for="take_from_stock" class="mt-2" />
                                <x-input-error for="return_to_stock" class="mt-2" />
                            </div>

                            <!-- Observações -->
                            <div class="col-span-2">
                                <x-label for="notes" value="{{ __('Observações') }}" />
                                <x-textarea id="notes" class="mt-1 block w-full" wire:model="notes" />
                                <x-input-error for="notes" class="mt-2" />
                            </div>
                        </div>

                        <!-- Produtos -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Produtos') }}</h3>
                            
                            <!-- Busca de Produtos -->
                            <div class="mb-6">
                                <x-input type="text" 
                                    class="w-full" 
                                    wire:model.live="search" 
                                    placeholder="Digite para buscar produtos..." />
                            </div>

                            <!-- Resultados da Busca -->
                            @if($searchResults && count($searchResults) > 0)
                                <div class="mb-6 bg-white shadow overflow-hidden sm:rounded-lg">
                                    <ul class="divide-y divide-gray-200">
                                        @foreach($searchResults as $product)
                                            <li class="p-4 hover:bg-gray-50 flex justify-between items-center">
                                                <div>
                                                    <div class="font-medium">{{ $product->name }}</div>
                                                    <div class="text-sm text-gray-500">SKU: {{ $product->sku }}</div>
                                                </div>
                                                <button type="button" 
                                                    wire:click="selectProduct({{ $product->id }})"
                                                    class="bg-green-600 text-white p-2 rounded-full hover:bg-green-700">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Produtos Selecionados -->
                            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                <ul class="divide-y divide-gray-200">
                                    @forelse($selectedProducts as $index => $product)
                                        <li class="p-4 flex items-center justify-between gap-4">
                                            <div class="flex-1">
                                                <div class="font-medium">{{ $product['name'] }}</div>
                                                <div class="text-sm text-gray-500">SKU: {{ $product['sku'] }}</div>
                                            </div>
                                            <div class="w-32">
                                                <x-input type="number" 
                                                    class="block w-full" 
                                                    wire:model="selectedProducts.{{ $index }}.quantity"
                                                    min="1" 
                                                    placeholder="Qtd" />
                                            </div>
                                            <button type="button" 
                                                wire:click="removeProduct({{ $index }})" 
                                                class="text-red-600 hover:text-red-900 p-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </li>
                                    @empty
                                        <li class="p-4 text-center text-gray-500">
                                            Nenhum produto selecionado
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-4">
                            <a href="{{ route('material-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancelar') }}
                            </a>
                            <x-button>
                                {{ __('Salvar') }}
                            </x-button>
                        </div>
                    </form>
</div>

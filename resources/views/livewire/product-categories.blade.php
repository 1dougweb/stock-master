<div>
    @if (session()->has('success'))
        <div class="mb-4 rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="space-y-4">
        <!-- Lista de Categorias Existentes -->
        <div class="space-y-2">
            @foreach($productCategories as $categoryId)
                @php
                    $category = $categories->find($categoryId);
                @endphp
                @if($category)
                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-md">
                        <span class="text-gray-700">{{ $category->name }}</span>
                        <button wire:click="removeCategory({{ $category->id }})" class="text-red-600 hover:text-red-900">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Adicionar Nova Categoria -->
        <div class="flex gap-2">
            <div class="flex-1">
                <select wire:model.defer="newCategory" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Selecione uma categoria</option>
                    @foreach($categories as $category)
                        @if(!in_array($category->id, $productCategories))
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <button wire:click="addCategory" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Adicionar
            </button>
        </div>

        <!-- Botão Salvar -->
        <div class="pt-4">
            <button wire:click="saveCategories" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                Salvar Categorias
            </button>
        </div>
    </div>
</div>

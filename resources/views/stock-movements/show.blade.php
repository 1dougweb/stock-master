<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Movimentação') }}
            </h2>
            <a href="{{ route('stock-movements.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
                {{ __('Voltar') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Informações da Movimentação') }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Data e hora</p>
                                    <p class="mt-1">{{ $stockMovement->created_at->format('d/m/Y H:i:s') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Tipo de movimento</p>
                                    <div class="mt-1">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $stockMovement->type === 'entrada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $stockMovement->type === 'entrada' ? 'Entrada' : 'Saída' }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Quantidade</p>
                                    <p class="mt-1">{{ $stockMovement->quantity }} {{ $stockMovement->product->unit_label }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Estoque anterior</p>
                                    <p class="mt-1">{{ $stockMovement->previous_stock }} {{ $stockMovement->product->unit_label }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Estoque atual</p>
                                    <p class="mt-1">{{ $stockMovement->new_stock }} {{ $stockMovement->product->unit_label }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Usuário responsável</p>
                                    <p class="mt-1">{{ $stockMovement->user->name }}</p>
                                </div>
                                @if($stockMovement->material_request_id)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Requisição de material</p>
                                        <p class="mt-1">
                                            <a href="{{ route('material-requests.show', $stockMovement->material_request_id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                #{{ $stockMovement->materialRequest->number }}
                                            </a>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Informações do Produto') }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nome do produto</p>
                                    <p class="mt-1">{{ $stockMovement->product->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">SKU</p>
                                    <p class="mt-1">{{ $stockMovement->product->sku }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Categoria</p>
                                    <p class="mt-1">{{ $stockMovement->product->category->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Fornecedor</p>
                                    <p class="mt-1">{{ $stockMovement->product->supplier->company_name ?? 'Não definido' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Estoque atual</p>
                                    <p class="mt-1">{{ $stockMovement->product->stock }} {{ $stockMovement->product->unit_label }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Estoque mínimo</p>
                                    <p class="mt-1">{{ $stockMovement->product->min_stock }} {{ $stockMovement->product->unit_label }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($stockMovement->notes)
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Observações') }}</h3>
                            <div class="mt-2 p-4 border border-gray-200 rounded-md">
                                <p>{{ $stockMovement->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">Bem-vindo ao Sistema de Gestão de Estoque</h1>
                    
                    <!-- Cards de resumo -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Produtos cadastrados -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-400 rounded-lg shadow-lg p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-white text-lg font-semibold">Produtos</h3>
                                    <p class="text-white text-3xl font-bold">{{ \App\Models\Product::count() }}</p>
                                </div>
                                <div class="p-3 bg-white/20 rounded-full">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-white/80 mt-2 text-sm">Total de produtos cadastrados</p>
                        </div>

                        <!-- Requisições pendentes -->
                        <div class="bg-gradient-to-r from-amber-600 to-amber-400 rounded-lg shadow-lg p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-white text-lg font-semibold">Requisições</h3>
                                    <p class="text-white text-3xl font-bold">{{ \App\Models\MaterialRequest::count() }}</p>
                                </div>
                                <div class="p-3 bg-white/20 rounded-full">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-white/80 mt-2 text-sm">Total de requisições</p>
                        </div>

                        <!-- Alertas de Estoque Baixo -->
                        <div class="bg-gradient-to-r from-red-600 to-red-400 rounded-lg shadow-lg p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-white text-lg font-semibold">Estoque Baixo</h3>
                                    <p class="text-white text-3xl font-bold">{{ \App\Models\Product::whereRaw('stock <= min_stock')->count() }}</p>
                                </div>
                                <div class="p-3 bg-white/20 rounded-full">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-white/80 mt-2 text-sm">Produtos abaixo do estoque mínimo</p>
                        </div>

                        <!-- Movimentações Recentes -->
                        <div class="bg-gradient-to-r from-green-600 to-green-400 rounded-lg shadow-lg p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-white text-lg font-semibold">Movimentações</h3>
                                    <p class="text-white text-3xl font-bold">{{ \App\Models\StockMovement::whereDate('created_at', \Carbon\Carbon::today())->count() }}</p>
                                </div>
                                <div class="p-3 bg-white/20 rounded-full">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-white/80 mt-2 text-sm">Movimentações realizadas hoje</p>
                        </div>
                    </div>

                    <!-- Seções principais -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Requisições recentes -->
                        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg shadow-md">
                            <div class="p-4 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-900">Requisições Recentes</h2>
                            </div>
                            <div class="p-4">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach(\App\Models\MaterialRequest::with('items')->latest()->take(5)->get() as $request)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            #{{ $request->number }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-500">
                                                            {{ $request->created_at->format('d/m/Y H:i') }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="{{ route('material-requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-900">
                                                            Detalhes
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-4 text-right">
                                    <a href="{{ route('material-requests.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                        Ver todas as requisições →
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Produtos em estoque baixo -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-md">
                            <div class="p-4 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-900">Produtos com Estoque Baixo</h2>
                            </div>
                            <div class="p-4">
                                <ul class="divide-y divide-gray-200">
                                    @foreach(\App\Models\Product::whereRaw('stock <= min_stock')->orderBy('stock', 'asc')->take(5)->get() as $product)
                                        <li class="py-3">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h3 class="text-sm font-medium text-gray-900">{{ $product->name }}</h3>
                                                    <p class="text-xs text-gray-500">SKU: {{ $product->sku }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm font-medium text-red-600">{{ $product->stock }} / {{ $product->min_stock }}</p>
                                                    <p class="text-xs text-gray-500">Estoque / Mínimo</p>
                                                </div>
                                            </div>
                                            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                                                @php
                                                    $percentage = $product->min_stock > 0 ? min(100, ($product->stock / $product->min_stock) * 100) : 0;
                                                    $colorClass = $percentage <= 30 ? 'bg-red-600' : ($percentage <= 70 ? 'bg-yellow-500' : 'bg-green-500');
                                                @endphp
                                                <div class="{{ $colorClass }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                
                                <div class="mt-4 text-right">
                                    <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                        Ver todos os produtos →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seção de Ações Rápidas -->
                    <div class="mt-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <a href="{{ route('material-requests.create') }}" class="flex items-center p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-all">
                                <div class="p-3 bg-indigo-100 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Nova Requisição</h3>
                                    <p class="text-xs text-gray-500">Criar nova requisição de material</p>
                                </div>
                            </a>

                            <a href="{{ route('products.create') }}" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-all">
                                <div class="p-3 bg-green-100 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Novo Produto</h3>
                                    <p class="text-xs text-gray-500">Adicionar produto ao estoque</p>
                                </div>
                            </a>

                            <a href="{{ route('stock-movements.create') }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all">
                                <div class="p-3 bg-blue-100 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Movimentação</h3>
                                    <p class="text-xs text-gray-500">Registrar entrada/saída manual</p>
                                </div>
                            </a>

                            <a href="{{ route('reports.index') }}" class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-all">
                                <div class="p-3 bg-purple-100 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Relatórios</h3>
                                    <p class="text-xs text-gray-500">Gerar relatórios de estoque</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

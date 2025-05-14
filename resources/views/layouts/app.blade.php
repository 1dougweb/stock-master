<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistema de Estoque') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts - Não adicione outros scripts que carregam Alpine.js -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            <!-- Left Sidebar Navigation -->
            <nav class="fixed left-0 top-0 h-full w-64 bg-indigo-800 text-white">
                <div class="p-6">
                    <h1 class="text-2xl font-bold">{{ config('app.name') }}</h1>
                </div>
                <div class="mt-6">
                    <a href="{{ url('/') }}" target="_self" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('dashboard') ? 'bg-indigo-700' : '' }}">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                    
                    <a href="{{ url('/products') }}" target="_self" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('products.*') ? 'bg-indigo-700' : '' }}">
                        <i class="fas fa-box mr-2"></i> Produtos
                    </a>
                    
                    <a href="{{ url('/categories') }}" target="_self" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('categories.*') ? 'bg-indigo-700' : '' }}">
                        <i class="fas fa-tags mr-2"></i> Categorias
                    </a>
                    
                    <a href="{{ url('/material-requests') }}" target="_self" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('material-requests.*') ? 'bg-indigo-700' : '' }}">
                        <i class="fas fa-clipboard-list mr-2"></i> Pedidos de Materiais
                    </a>
                    
                    <a href="{{ url('/suppliers') }}" target="_self" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('suppliers.*') ? 'bg-indigo-700' : '' }}">
                        <i class="fas fa-truck mr-2"></i> Fornecedores
                    </a>
                    
                    <a href="{{ url('/employees') }}" target="_self" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('employees.*') ? 'bg-indigo-700' : '' }}">
                        <i class="fas fa-users mr-2"></i> Funcionários
                    </a>
                    
                    <a href="{{ url('/reports') }}" target="_self" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('reports.*') ? 'bg-indigo-700' : '' }}">
                        <i class="fas fa-chart-bar mr-2"></i> Relatórios
                    </a>
                </div>
            </nav>

            <!-- Right Side Analytics -->
            <div class="fixed right-0 top-0 h-full w-64 bg-white shadow-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold mb-4">Analytics</h2>
                    <div class="space-y-6">
                        <!-- Stock Status -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Status do Estoque</h3>
                            <div class="bg-gray-100 rounded p-4">
                                <p class="text-2xl font-bold text-indigo-600">{{ \App\Models\Product::count() }}</p>
                                <p class="text-sm text-gray-600">Produtos Cadastrados</p>
                            </div>
                        </div>

                        <!-- Low Stock Alerts -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Alertas</h3>
                            <div class="bg-red-100 rounded p-4">
                                <p class="text-2xl font-bold text-red-600">{{ \App\Models\Product::whereRaw('stock <= min_stock')->count() }}</p>
                                <p class="text-sm text-gray-600">Produtos com Estoque Baixo</p>
                            </div>
                        </div>

                        <!-- Recent Activities -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Atividades Recentes</h3>
                            <div class="space-y-2">
                                @foreach(\App\Models\StockMovement::latest()->limit(5)->get() as $movement)
                                    <div class="text-sm">
                                        <p class="font-medium">{{ $movement->product->name }}</p>
                                        <p class="text-gray-500">{{ $movement->type }} - {{ $movement->quantity }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="ml-64 mr-64">
                @livewire('navigation-menu')

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
        
        <!-- Scripts personalizados - Cuidado para não incluir Alpine.js novamente -->
        @stack('scripts')
    </body>
</html>

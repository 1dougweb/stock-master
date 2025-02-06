<div>
    <div class="mb-6">
        <div class="flex justify-end space-x-4">
            <select wire:model="period" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="week">Últimos 7 dias</option>
                <option value="month">Último mês</option>
                <option value="year">Último ano</option>
            </select>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total de Requisições</h3>
                    <p class="text-3xl font-bold text-indigo-600">{{ $monthlyStats['total_orders'] }}</p>
                    <p class="text-sm text-gray-500">Este mês</p>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Requisições</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $monthlyStats['completed_orders'] }}</p>
                    <p class="text-sm text-gray-500">Este mês</p>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Movimentações</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $monthlyStats['total_movements'] }}</p>
                    <p class="text-sm text-gray-500">Este mês</p>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Alertas Estoque</h3>
                    <p class="text-3xl font-bold text-red-600">{{ $monthlyStats['low_stock_alerts'] }}</p>
                    <p class="text-sm text-gray-500">Produtos abaixo do mínimo</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Gráfico de Requisições -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Requisições por Período</h2>
            <div wire:ignore>
                <div id="ordersChart" style="height: 300px;"></div>
            </div>
        </div>

        <!-- Gráfico de Movimentações -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Movimentações de Estoque</h2>
            <div wire:ignore>
                <div id="stockMovementsChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Produtos Mais Movimentados -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Produtos Mais Movimentados</h2>
                <div wire:ignore>
                    <div id="topProductsChart" style="height: 300px;"></div>
                </div>
            </div>
            <div class="mt-4 space-y-4">
                @foreach($topProducts as $product)
                    <div class="flex items-center justify-between border-b pb-2">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $product->name }}</h4>
                            <p class="text-sm text-gray-500">Estoque atual: {{ $product->stock }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">{{ $product->stock_movements_count }} movimentações</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Últimas Movimentações -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Últimas Movimentações</h2>
            <div class="space-y-4">
                @foreach($stockMovements as $movement)
                    <div class="flex items-center justify-between border-b pb-2">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $movement->product->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $movement->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium {{ $movement->type === 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $movement->type === 'entrada' ? '+' : '-' }}{{ $movement->quantity }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mt-8 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Requisições de Material Recentes</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Número</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentOrders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $order->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->client_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $order->status === 'concluida' ? 'bg-green-100 text-green-800' :
                                       ($order->status === 'pendente' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                R$ {{ number_format($order->total_amount, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            // Gráfico de Requisições
            var ordersOptions = {
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: { show: false }
                },
                series: [{
                    name: 'Requisições',
                    data: @json(array_values($chartData['orders']))
                }],
                xaxis: {
                    categories: @json(array_keys($chartData['orders'])),
                    labels: { rotate: -45 }
                },
                yaxis: {
                    labels: {
                        formatter: function (value) {
                            return Math.round(value);
                        }
                    }
                },
                colors: ['#6366F1'],
                stroke: { curve: 'smooth' },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3
                    }
                }
            };

            var ordersChart = new ApexCharts(document.querySelector("#ordersChart"), ordersOptions);
            ordersChart.render();

            // Gráfico de Movimentações de Estoque
            var stockMovementsOptions = {
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: { show: false }
                },
                series: [{
                    name: 'Entradas',
                    data: @json(array_values($chartData['stockIn']))
                }, {
                    name: 'Saídas',
                    data: @json(array_values($chartData['stockOut']))
                }],
                xaxis: {
                    categories: @json(array_keys($chartData['stockIn'])),
                    labels: { rotate: -45 }
                },
                colors: ['#10B981', '#EF4444'],
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                    }
                }
            };

            var stockMovementsChart = new ApexCharts(document.querySelector("#stockMovementsChart"), stockMovementsOptions);
            stockMovementsChart.render();

            // Gráfico de Produtos Mais Movimentados
            var topProductsOptions = {
                chart: {
                    type: 'donut',
                    height: 300
                },
                series: @json($topProducts->pluck('stock_movements_count')),
                labels: @json($topProducts->pluck('name')),
                colors: ['#6366F1', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                legend: {
                    position: 'bottom'
                }
            };

            var topProductsChart = new ApexCharts(document.querySelector("#topProductsChart"), topProductsOptions);
            topProductsChart.render();

            // Atualizar gráficos quando os dados mudarem
            Livewire.on('updateCharts', data => {
                ordersChart.updateSeries([{
                    data: Object.values(data.orders)
                }]);
                ordersChart.updateOptions({
                    xaxis: {
                        categories: Object.keys(data.orders)
                    }
                });

                stockMovementsChart.updateSeries([{
                    data: Object.values(data.stockIn)
                }, {
                    data: Object.values(data.stockOut)
                }]);
                stockMovementsChart.updateOptions({
                    xaxis: {
                        categories: Object.keys(data.stockIn)
                    }
                });

                topProductsChart.updateOptions({
                    series: data.topProducts.map(p => p.stock_movements_count),
                    labels: data.topProducts.map(p => p.name)
                });
            });
        });
    </script>
    @endpush
</div>

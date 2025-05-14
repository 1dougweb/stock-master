<div>
    <div class="mb-6">
        <div class="flex justify-end space-x-4">
            <select wire:model.live="period" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
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
            <div class="chart-container" style="position: relative; height:300px;">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>

        <!-- Gráfico de Movimentações -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Movimentações de Estoque</h2>
            <div class="chart-container" style="position: relative; height:300px;">
                <canvas id="stockMovementsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Produtos Mais Movimentados -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Produtos Mais Movimentados</h2>
                <div class="chart-container" style="position: relative; height:300px;">
                    <canvas id="topProductsChart"></canvas>
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

    <!-- Embedded Chart.js library directly to avoid CDN loading issues -->
    <script>
        // Better loading approach for Chart.js
        if (typeof Chart === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js';
            script.async = false; // We need this to be synchronous
            script.onload = function() {
                // Initialize charts once loaded
                if (document.readyState === 'complete' || document.readyState === 'interactive') {
                    setTimeout(initializeCharts, 200);
                }
            };
            document.head.appendChild(script);
        }
    </script>
    
    <script>
        // Charts instances to manage them globally
        let ordersChart = null;
        let stockMovementsChart = null;
        let topProductsChart = null;
        
        // Initialize the charts when Livewire is ready
        document.addEventListener('livewire:initialized', function() {
            console.log('Livewire initialized, preparing charts');
            
            // Check if Chart.js is already loaded
            if (typeof Chart !== 'undefined') {
                setTimeout(initializeCharts, 200);
            }
            
            // Livewire v3 event listener for chart data updates
            Livewire.on('chartDataUpdated', (data) => {
                console.log('Updating charts with new data:', data);
                initializeCharts();
            });
            
            // Also listen for updateCharts event (compatibility with original code)
            Livewire.on('updateCharts', (data) => {
                console.log('Updating charts with new data (updateCharts):', data);
                initializeCharts();
            });
        });
        
        // Function to initialize or update the charts
        function initializeCharts() {
            console.log('Initializing charts...');
            
            try {
                // Data for the charts
                const orders = @json($chartData['orders'] ?? []);
                const stockIn = @json($chartData['stockIn'] ?? []);
                const stockOut = @json($chartData['stockOut'] ?? []);
                const topProducts = @json($chartData['topProducts'] ?? []);
                
                // Colors for the charts
                const colors = {
                    primary: '#4F46E5',
                    success: '#10B981',
                    danger: '#EF4444',
                    chartColors: ['#4F46E5', '#10B981', '#EF4444', '#F59E0B', '#3B82F6', '#8B5CF6']
                };
                
                // Destroy existing charts before creating new ones
                destroyExistingCharts();
                
                // 1. Orders Line Chart
                createOrdersChart(orders, colors);
                
                // 2. Stock Movements Bar Chart
                createStockMovementsChart(stockIn, stockOut, colors);
                
                // 3. Top Products Doughnut Chart
                createTopProductsChart(topProducts, colors);
                
            } catch (error) {
                console.error('Error initializing charts:', error);
            }
        }
        
        // Destroy existing charts to prevent duplicates
        function destroyExistingCharts() {
            try {
                [
                    { chart: ordersChart, id: 'ordersChart' },
                    { chart: stockMovementsChart, id: 'stockMovementsChart' },
                    { chart: topProductsChart, id: 'topProductsChart' }
                ].forEach(item => {
                    if (item.chart) {
                        item.chart.destroy();
                        item.chart = null;
                    } else {
                        const chartElement = document.getElementById(item.id);
                        if (chartElement) {
                            const existingChart = Chart.getChart(chartElement);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error destroying charts:', error);
            }
        }
        
        // Create Orders Line Chart
        function createOrdersChart(orders, colors) {
            const ordersCtx = document.getElementById('ordersChart');
            if (!ordersCtx) return;
            
            console.log('Rendering orders chart');
            ordersChart = new Chart(ordersCtx, {
                type: 'line',
                data: {
                    labels: Object.keys(orders),
                    datasets: [{
                        label: 'Requisições',
                        data: Object.values(orders),
                        borderColor: colors.primary,
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }
        
        // Create Stock Movements Bar Chart
        function createStockMovementsChart(stockIn, stockOut, colors) {
            const stockCtx = document.getElementById('stockMovementsChart');
            if (!stockCtx) return;
            
            console.log('Rendering stock movements chart');
            // Combine all dates
            const allDates = [...new Set([...Object.keys(stockIn), ...Object.keys(stockOut)])].sort();
            
            stockMovementsChart = new Chart(stockCtx, {
                    type: 'bar',
                data: {
                    labels: allDates,
                    datasets: [
                        {
                            label: 'Entradas',
                            data: allDates.map(date => stockIn[date] || 0),
                            backgroundColor: colors.success
                        },
                        {
                            label: 'Saídas',
                            data: allDates.map(date => stockOut[date] || 0),
                            backgroundColor: colors.danger
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }
        
        // Create Top Products Doughnut Chart
        function createTopProductsChart(topProducts, colors) {
            const productsCtx = document.getElementById('topProductsChart');
            if (!productsCtx) return;
            
            console.log('Rendering top products chart');
            topProductsChart = new Chart(productsCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(topProducts),
                    datasets: [{
                        data: Object.values(topProducts),
                        backgroundColor: colors.chartColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                legend: {
                            position: 'right',
                            labels: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }
    </script>
</div>

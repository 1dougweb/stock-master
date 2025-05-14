<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\MaterialRequest;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $period = 'month';
    public $chartData = [];
    public $lowStockProducts = [];
    public $recentOrders = [];
    public $stockMovements = [];
    public $statistics = [];
    public $monthlyStats = [];
    public $topProducts = [];

    protected $listeners = ['refreshDashboard' => '$refresh'];

    public function mount()
    {
        $this->loadData();
    }

    public function updatedPeriod()
    {
        // Log da mudança de período para depuração
        \Illuminate\Support\Facades\Log::info('Período atualizado para: ' . $this->period);
        
        $this->loadData();
        
        // Preparar os dados para os gráficos, incluindo os rótulos 
        $updateData = [
            'orders' => $this->chartData['orders'],
            'stockIn' => $this->chartData['stockIn'],
            'stockOut' => $this->chartData['stockOut'],
            'topProducts' => $this->topProducts->pluck('stock_movements_count', 'name')->toArray(),
            'period' => $this->period
        ];
        
        // Despachar o evento com os dados dos gráficos
        // Dispatching to both event names for compatibility
        $this->dispatch('updateCharts', $updateData);
        $this->dispatch('chartDataUpdated', $this->chartData);
    }

    protected function loadData()
    {
        $this->loadStatistics();
        $this->loadChartData();
        $this->loadLowStockProducts();
        $this->loadRecentOrders();
        $this->loadStockMovements();
        $this->loadMonthlyStats();
        $this->loadTopProducts();
    }

    protected function loadMonthlyStats()
    {
        $startDate = Carbon::now()->startOfMonth();
        
        $stats = MaterialRequest::select(
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(CASE WHEN status = "concluida" THEN 1 ELSE 0 END) as completed_orders'),
            DB::raw('SUM(total_amount) as total_amount'),
            DB::raw('AVG(total_amount) as average_amount')
        )
            ->whereBetween('created_at', [$startDate, now()])
            ->first();

        $stockMovements = StockMovement::whereBetween('created_at', [$startDate, now()])->count();
        $lowStockAlerts = Product::whereRaw('stock <= min_stock')->count();

        $this->monthlyStats = [
            'total_orders' => $stats->total_orders ?? 0,
            'completed_orders' => $stats->completed_orders ?? 0,
            'total_amount' => $stats->total_amount ?? 0,
            'average_amount' => $stats->average_amount ?? 0,
            'total_movements' => $stockMovements,
            'low_stock_alerts' => $lowStockAlerts
        ];
    }

    protected function loadStatistics()
    {
        $startDate = $this->getStartDate();

        $this->statistics = [
            'total_orders' => MaterialRequest::whereBetween('created_at', [$startDate, now()])->count(),
            'pending_orders' => MaterialRequest::where('status', 'pendente')
                ->whereBetween('created_at', [$startDate, now()])
                ->count(),
            'completed_orders' => MaterialRequest::where('status', 'concluida')
                ->whereBetween('created_at', [$startDate, now()])
                ->count(),
            'total_amount' => MaterialRequest::whereBetween('created_at', [$startDate, now()])
                ->sum('total_amount'),
            'total_movements' => StockMovement::whereBetween('created_at', [$startDate, now()])->count(),
            'low_stock_products' => Product::whereRaw('stock <= min_stock')->count(),
        ];
    }

    protected function loadChartData()
    {
        $startDate = $this->getStartDate();
        
        // Dados para o gráfico de requisições
        $orders = MaterialRequest::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
            ->whereBetween('created_at', [$startDate, now()])
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        // Dados para o gráfico de movimentações
        $stockIn = StockMovement::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
            ->where('type', 'entrada')
            ->whereBetween('created_at', [$startDate, now()])
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        $stockOut = StockMovement::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
            ->where('type', 'saida')
            ->whereBetween('created_at', [$startDate, now()])
            ->groupBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        // Preencher datas faltantes com zeros
        $dates = collect(array_unique(array_merge(
            array_keys($orders),
            array_keys($stockIn),
            array_keys($stockOut)
        )))->sort();

        $filledOrders = [];
        $filledStockIn = [];
        $filledStockOut = [];

        foreach ($dates as $date) {
            $filledOrders[$date] = $orders[$date] ?? 0;
            $filledStockIn[$date] = $stockIn[$date] ?? 0;
            $filledStockOut[$date] = $stockOut[$date] ?? 0;
        }

        $this->chartData = [
            'orders' => $filledOrders,
            'stockIn' => $filledStockIn,
            'stockOut' => $filledStockOut
        ];
    }

    protected function loadLowStockProducts()
    {
        $this->lowStockProducts = Product::whereRaw('stock <= min_stock')
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();
    }

    protected function loadRecentOrders()
    {
        $this->recentOrders = MaterialRequest::with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    protected function loadStockMovements()
    {
        $this->stockMovements = StockMovement::with('product')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    protected function loadTopProducts()
    {
        $this->topProducts = Product::withCount('stockMovements')
            ->orderBy('stock_movements_count', 'desc')
            ->take(5)
            ->get();
    }

    protected function getStartDate()
    {
        return match($this->period) {
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'year' => now()->subYear(),
            default => now()->subMonth(),
        };
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}

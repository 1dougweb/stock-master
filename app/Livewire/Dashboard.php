<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\MaterialRequest;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $monthlyStats;
    public $topProducts;

    public function mount()
    {
        $this->loadMonthlyStats();
        $this->loadTopProducts();
    }

    private function loadMonthlyStats()
    {
        $this->monthlyStats = [
            'total_orders' => MaterialRequest::whereMonth('created_at', now()->month)->count(),
            'completed_orders' => MaterialRequest::whereMonth('created_at', now()->month)
                ->where('status', 'completed')
                ->count(),
            'total_movements' => StockMovement::whereMonth('created_at', now()->month)->count(),
            'low_stock_alerts' => Product::whereRaw('stock <= min_stock')->count(),
        ];
    }

    private function loadTopProducts()
    {
        $this->topProducts = StockMovement::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->where('type', 'saida')
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        $lowStockProducts = Product::whereRaw('stock <= min_stock')
            ->orderBy('stock')
            ->limit(5)
            ->get();

        $recentOrders = MaterialRequest::with(['user', 'products'])
            ->latest()
            ->limit(5)
            ->get();

        $recentMovements = StockMovement::with(['product', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        $totalProducts = Product::count();
        $totalOrders = MaterialRequest::count();
        $pendingOrders = MaterialRequest::where('status', 'pending')->count();

        return view('livewire.dashboard', [
            'lowStockProducts' => $lowStockProducts,
            'recentOrders' => $recentOrders,
            'recentMovements' => $recentMovements,
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'monthlyStats' => $this->monthlyStats,
            'topProducts' => $this->topProducts,
        ]);
    }
}

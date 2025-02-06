<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\MaterialRequest;
use App\Models\StockMovement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function stock(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->stock_status === 'low') {
            $query->whereRaw('stock <= min_stock');
        } elseif ($request->stock_status === 'out') {
            $query->where('stock', 0);
        }

        $products = $query->orderBy('name')->get();

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('reports.stock-pdf', compact('products'));
            return $pdf->download('relatorio-estoque.pdf');
        }

        return view('reports.stock', compact('products'));
    }

    public function movements(Request $request)
    {
        $query = StockMovement::with(['product', 'user', 'serviceOrder']);

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        $movements = $query->latest()->get();

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('reports.movements-pdf', compact('movements'));
            return $pdf->download('relatorio-movimentacoes.pdf');
        }

        return view('reports.movements', compact('movements'));
    }

    public function orders(Request $request)
    {
        $query = MaterialRequest::with(['user', 'items.product']);

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('reports.orders-pdf', compact('orders'));
            return $pdf->download('relatorio-ordens-servico.pdf');
        }

        return view('reports.orders', compact('orders'));
    }

    public function expenses(Request $request)
    {
        $query = MaterialRequest::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(total_amount) as total_amount')
        )
        ->whereNotNull('total_amount')
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc');

        if ($request->year) {
            $query->whereYear('created_at', $request->year);
        }

        $expenses = $query->get()->map(function ($item) {
            $item->month_name = date('F', mktime(0, 0, 0, $item->month, 1));
            return $item;
        });

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('reports.expenses-pdf', compact('expenses'));
            return $pdf->download('relatorio-despesas.pdf');
        }

        return view('reports.expenses', compact('expenses'));
    }
}

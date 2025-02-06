<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MaterialRequestController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockMovementController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Produtos
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('products/{product}/stock-history', [ProductController::class, 'stockHistory'])->name('products.stock-history');
    Route::post('products/generate-sku', [ProductController::class, 'generateSKU'])->name('products.generate-sku');

    // Categorias
    Route::resource('categories', CategoryController::class);

    // Requisições de Material
    Route::get('material-requests', [MaterialRequestController::class, 'index'])->name('material-requests.index');
    Route::get('material-requests/create', [MaterialRequestController::class, 'create'])->name('material-requests.create');
    Route::post('material-requests', [MaterialRequestController::class, 'store'])->name('material-requests.store');
    Route::get('material-requests/{materialRequest}', [MaterialRequestController::class, 'show'])->name('material-requests.show');
    Route::get('material-requests/{materialRequest}/edit', [MaterialRequestController::class, 'edit'])->name('material-requests.edit');
    Route::put('material-requests/{materialRequest}', [MaterialRequestController::class, 'update'])->name('material-requests.update');
    Route::delete('material-requests/{materialRequest}', [MaterialRequestController::class, 'destroy'])->name('material-requests.destroy');
    Route::post('material-requests/{materialRequest}/complete', [MaterialRequestController::class, 'complete'])->name('material-requests.complete');
    Route::get('material-requests/{materialRequest}/pdf', [MaterialRequestController::class, 'generatePDF'])->name('material-requests.pdf');

    // Fornecedores
    Route::resource('suppliers', SupplierController::class);
    Route::post('suppliers/fetch-cnpj', [SupplierController::class, 'fetchCNPJ'])->name('suppliers.fetch-cnpj');

    // Funcionários
    Route::resource('employees', EmployeeController::class);

    // Movimentações de Estoque
    Route::resource('stock-movements', StockMovementController::class);

    // Relatórios
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
    Route::get('reports/movements', [ReportController::class, 'movements'])->name('reports.movements');
    Route::get('reports/orders', [ReportController::class, 'orders'])->name('reports.orders');
    Route::get('reports/expenses', [ReportController::class, 'expenses'])->name('reports.expenses');
});

// Rotas de Autenticação
Route::middleware('guest')->group(function () {
    Route::get('login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('login', function () {
        $credentials = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials)) {
            request()->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ]);
    });
});

Route::post('logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

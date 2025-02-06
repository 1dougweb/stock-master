<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Collection;

class StockNotificationService
{
    public function checkLowStock(): Collection
    {
        return Product::whereRaw('stock <= min_stock')
            ->with(['category', 'supplier'])
            ->get();
    }

    public function notifyAdmins(Collection $products): void
    {
        if ($products->isEmpty()) {
            return;
        }

        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new LowStockNotification($products));
        }
    }

    public function handle(): void
    {
        $lowStockProducts = $this->checkLowStock();
        $this->notifyAdmins($lowStockProducts);
    }
}

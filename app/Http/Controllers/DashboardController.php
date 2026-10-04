<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. آمارهای پایه
        $totalProducts = Product::count();
        $totalWarehouses = Warehouse::count();
        $totalUsers = User::count();

        // 2. محاسبه کالاهای دارای کسری موجودی (بر اساس نقطه سفارش reorder_level)
        $lowStockQuery = WarehouseStock::whereHas('product', function ($q) {
            $q->whereColumn('warehouse_stocks.quantity', '<=', 'products.reorder_level');
        });

        if ($user && $user->role && $user->role->name === 'انباردار') {
            $allowedWarehouseIds = $user->warehouses->pluck('id')->toArray();
            $lowStockQuery->whereIn('warehouse_id', $allowedWarehouseIds);
        }

        $lowStockCount = $lowStockQuery->count();

        // 3. آمارهای مالی (خرید و فروش)
        $invoiceQuery = Invoice::query();
        if ($user && $user->role && $user->role->name === 'انباردار') {
            $allowedWarehouseIds = $user->warehouses->pluck('id')->toArray();
            $invoiceQuery->whereIn('warehouse_id', $allowedWarehouseIds);
        }

        $totalPurchaseAmount = (clone $invoiceQuery)->where('type', 'purchase')->sum('total_amount');
        $totalSaleAmount = (clone $invoiceQuery)->where('type', 'sale')->sum('total_amount');
        $netProfit = $totalSaleAmount - $totalPurchaseAmount;

        // 4. آخرین فاکتورها (تراکنش‌های اخیر)
        $recentInvoices = (clone $invoiceQuery)
            ->with(['warehouse', 'supplier', 'user'])
            ->latest()
            ->take(6)
            ->get();

        // 5. آمار نمودار ماهانه (برای ۶ ماه اخیر)
        $monthlyStats = Invoice::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(CASE WHEN type = "purchase" THEN total_amount ELSE 0 END) as purchases'),
            DB::raw('SUM(CASE WHEN type = "sale" THEN total_amount ELSE 0 END) as sales')
        )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalWarehouses',
            'totalUsers',
            'lowStockCount',
            'totalPurchaseAmount',
            'totalSaleAmount',
            'netProfit',
            'recentInvoices',
            'monthlyStats'
        ));
    }
}

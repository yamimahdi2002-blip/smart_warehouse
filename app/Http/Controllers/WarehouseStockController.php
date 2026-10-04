<?php

namespace App\Http\Controllers;

use App\Models\WarehouseStock;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseStockController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = WarehouseStock::with(['product.category', 'warehouse']);

        // اگر انباردار است، فقط موجودی انبارهای مجاز خودش را می‌بیند
        if ($user && $user->role && $user->role->name === 'انباردار') {
            $allowedWarehouseIds = $user->warehouses->pluck('id')->toArray();
            $query->whereIn('warehouse_id', $allowedWarehouseIds);
        }

        // فیلتر بر اساس انبار
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        // فیلتر کالاهای دارای هشدار کسر موجودی
        if ($request->filled('low_stock') && $request->low_stock == '1') {
            $query->whereHas('product', function ($q) {
                $q->whereColumn('warehouse_stocks.quantity', '<=', 'products.reorder_level');
            });
        }

        $stocks = $query->latest()->paginate(15);

        $warehouses = ($user && $user->role && $user->role->name === 'انباردار')
            ? $user->warehouses
            : Warehouse::all();

        return view('stocks.index', compact('stocks', 'warehouses'));
    }
}

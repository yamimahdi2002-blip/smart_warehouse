<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Invoice::with(['warehouse', 'supplier', 'user']);

        // انباردار فقط فاکتورهای انبارهای مجاز خودش را می‌بیند
        if ($user && $user->role && $user->role->name === 'انباردار') {
            $allowedWarehouseIds = $user->warehouses->pluck('id')->toArray();
            $query->whereIn('warehouse_id', $allowedWarehouseIds);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $invoices = $query->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $user = auth()->user();

        // انباردار فقط لیست انبارهای مجاز خود را برای انتخاب می‌بیند
        if ($user && $user->role && $user->role->name === 'انباردار') {
            $warehouses = $user->warehouses;
        } else {
            $warehouses = Warehouse::all();
        }

        $suppliers = Supplier::all();
        $products = Product::all();

        return view('invoices.create', compact('warehouses', 'suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:purchase,sale',
            'warehouse_id' => 'required|exists:warehouses,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // کنترل سخت‌گیرانه موجودی قبل از ثبت فاکتور فروش
        if ($request->type === 'sale') {
            foreach ($request->items as $item) {
                $stock = WarehouseStock::where('warehouse_id', $request->warehouse_id)
                    ->where('product_id', $item['product_id'])
                    ->first();

                $currentQty = $stock ? $stock->quantity : 0;

                if ($currentQty < $item['quantity']) {
                    $productName = Product::find($item['product_id'])->name ?? 'کالا';
                    return back()->withInput()->withErrors([
                        'items' => "موجودی کالای «{$productName}» در این انبار کافی نیست. (موجودی فعلی: {$currentQty})"
                    ]);
                }
            }
        }

        DB::transaction(function () use ($request) {
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += $item['quantity'] * $item['unit_price'];
            }

            $invoice = Invoice::create([
                'invoice_number' => $request->invoice_number,
                'type' => $request->type,
                'warehouse_id' => $request->warehouse_id,
                'supplier_id' => $request->type === 'purchase' ? $request->supplier_id : null,
                'user_id' => auth()->id(),
                'total_amount' => $totalAmount,
                'invoice_date' => now(),
            ]);

            foreach ($request->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal'   => $item['quantity'] * $item['unit_price'],
                ]);

                $stock = WarehouseStock::firstOrCreate(
                    [
                        'warehouse_id' => $request->warehouse_id,
                        'product_id'   => $item['product_id'],
                    ],
                    ['quantity' => 0]
                );

                if ($request->type === 'purchase') {
                    $stock->increment('quantity', $item['quantity']);
                } else {
                    $stock->decrement('quantity', $item['quantity']);
                }
            }
        });

        return redirect()->route('invoices.index')->with('success', 'فاکتور با موفقیت ثبت شد و موجودی انبار بروزرسانی گردید.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['warehouse', 'supplier', 'user', 'items.product']);
        return view('invoices.show', compact('invoice'));
    }
}

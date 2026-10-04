<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::with(['users', 'stocks'])->latest()->paginate(10);
        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
         // دریافت لیست انبارداران برای تخصیص
        $keepers = User::whereHas('role', function ($query) {
            $query->where('name', 'انباردار');
        })->get();

        return view('warehouses.create', compact('keepers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'keeper_ids' => 'nullable|array',
            'keeper_ids.*' => 'exists:users,id',
        ], [
            'name.required' => 'لطفاً نام انبار را وارد کنید.',
        ]);

        $warehouse = Warehouse::create([
            'name' => $validated['name'],
            'location' => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        if (!empty($validated['keeper_ids'])) {
            $warehouse->users()->sync($validated['keeper_ids']);
        }

        return redirect()->route('warehouses.index')->with('success', 'انبار جدید با موفقیت ایجاد شد.');
    }

    public function edit(Warehouse $warehouse)
    {
        $keepers = User::whereHas('role', function ($query) {
            $query->where('name', 'انباردار');
        })->get();

        $assignedKeepers = $warehouse->users->pluck('id')->toArray();

        return view('warehouses.edit', compact('warehouse', 'keepers', 'assignedKeepers'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'keeper_ids' => 'nullable|array',
            'keeper_ids.*' => 'exists:users,id',
        ], [
            'name.required' => 'لطفاً نام انبار را وارد کنید.',
        ]);

        $warehouse->update([
            'name' => $validated['name'],
            'location' => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        $warehouse->users()->sync($validated['keeper_ids'] ?? []);

        return redirect()->route('warehouses.index')->with('success', 'اطلاعات انبار با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('warehouses.index')->with('success', 'انبار مورد نظر حذف شد.');
    }
}

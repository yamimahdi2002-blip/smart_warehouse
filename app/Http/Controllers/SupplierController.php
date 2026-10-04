<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

//        if ($request->filled('search')) {
//            $search = $request->search;
//            $query->where('name', 'like', "%{$search}%")
//                ->orWhere('phone', 'like', "%{$search}%");
//        }

        $suppliers = $query->latest()->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name'   => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone'          => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string',
        ], [
            'company_name.required'   => 'نام شرکت الزامی است.',
            'contact_person.required' => 'نام شخص رابط الزامی است.',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'تامین‌کننده با موفقیت ثبت شد.');
    }
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'company_name'   => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone'          => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')->with('success', 'اطلاعات تامین‌کننده بروزرسانی شد.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'تامین‌کننده حذف شد.');
    }
}

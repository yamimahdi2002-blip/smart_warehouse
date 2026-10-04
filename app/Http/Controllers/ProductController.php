<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'barcode' => 'required|string|max:100|unique:products,barcode',
            'unit' => 'required|string|max:50',
            'reorder_level' => 'required|integer|min:0',
        ], [
            'name.required' => 'نام کالا الزامی است.',
            'barcode.required' => 'کد بارکد الزامی است.',
            'barcode.unique' => 'این بارکد تکراری است.',
            'category_id.required' => 'انتخاب دسته‌بندی الزامی است.',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'کالا با موفقیت ثبت شد.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'barcode' => 'required|string|max:100|unique:products,barcode,' . $product->id,
            'unit' => 'required|string|max:50',
            'reorder_level' => 'required|integer|min:0',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'اطلاعات کالا با موفقیت بروزرسانی شد.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'کالا با موفقیت حذف شد.');
    }

    public function printBarcode(Product $product)
    {
        return view('products.barcode', compact('product'));
    }
}

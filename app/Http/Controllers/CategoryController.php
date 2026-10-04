<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['parent', 'products'])->latest()->paginate(10);
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('categories.index', compact('categories', 'parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ], [
            'name.required' => 'نام دسته‌بندی الزامی است.',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'دسته‌بندی با موفقیت ایجاد شد.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'امکان حذف این دسته‌بندی به دلیل وجود کالا در آن وجود ندارد.');
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'دسته‌بندی با موفقیت حذف شد.');
    }
}

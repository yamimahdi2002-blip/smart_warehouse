@extends('layouts.app')

@section('title', 'تعریف و مدیریت کالاها')

@section('content')
    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">📦</span>
                    لیست کالاهای سیستم
                </h1>
                <p class="text-xs text-slate-500 mt-1">تعریف محصولات، کد بارکد و تعیین حداقل موجودی</p>
            </div>
            <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-xl shadow-lg shadow-indigo-600/20 transition">
                <span>+</span> تعریف کالای جدید
            </a>
        </div>

        <form action="{{ route('products.index') }}" method="GET" class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="جستجو بر اساس نام کالا یا بارکد..."
                       class="w-full px-4 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div class="w-48">
                <select name="category_id" class="w-full px-4 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                    <option value="">همه دسته‌بندی‌ها</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs px-5 py-2.5 rounded-xl transition">
                اعمال فیلتر
            </button>
        </form>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm p-4 rounded-xl">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <table class="w-full text-right text-sm">
                <thead>
                <tr class="text-xs font-semibold text-slate-400 border-b border-slate-100 bg-slate-50/50">
                    <th class="py-3.5 px-4">نام کالا</th>
                    <th class="py-3.5 px-4">دسته‌بندی</th>
                    <th class="py-3.5 px-4">کد بارکد</th>
                    <th class="py-3.5 px-4">واحد سنجش</th>
                    <th class="py-3.5 px-4">نقطه سفارش (هشدار)</th>
                    <th class="py-3.5 px-4 text-center">عملیات</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                @foreach($products as $product)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-4 font-bold text-slate-900">{{ $product->name }}</td>
                        <td class="py-4 px-4 text-xs font-medium text-slate-600">{{ $product->category->name ?? '-' }}</td>
                        <td class="py-4 px-4 font-mono text-xs text-indigo-600">{{ $product->barcode }}</td>
                        <td class="py-4 px-4 text-xs">{{ $product->unit }}</td>
                        <td class="py-4 px-4 text-xs font-bold text-amber-600">{{ $product->reorder_level }} {{ $product->unit }}</td>
                        <td class="py-4 px-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('products.barcode', $product->id) }}" target="_blank" class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition" title="چاپ بارکد">
                                    🏷️
                                </a>
                                <a href="{{ route('products.edit', $product->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="ویرایش">
                                    ✏️
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('آیا از حذف مطمئن هستید؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        </div>

    </div>
@endsection

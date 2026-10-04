@extends('layouts.app')

@section('title', 'مدیریت دسته‌بندی‌ها')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm h-fit">
            <h2 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span>📁</span> تعریف دسته‌بندی جدید
            </h2>

            <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">نام دسته‌بندی <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="مثال: قطعات الکترونیکی"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">دسته والد (اختیاری)</label>
                    <select name="parent_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                        <option value="">بدون والد (دسته اصلی)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium py-2.5 rounded-xl shadow-lg shadow-indigo-600/20 transition">
                    ثبت دسته‌بندی
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 space-y-4">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm p-4 rounded-xl">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-rose-50 border border-rose-200 text-rose-800 text-sm p-4 rounded-xl">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <table class="w-full text-right text-sm">
                    <thead>
                    <tr class="text-xs font-semibold text-slate-400 border-b border-slate-100 bg-slate-50/50">
                        <th class="py-3.5 px-4">عنوان دسته</th>
                        <th class="py-3.5 px-4">دسته والد</th>
                        <th class="py-3.5 px-4">تعداد کالاها</th>
                        <th class="py-3.5 px-4 text-center">عملیات</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                    @foreach($categories as $category)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-4 px-4 font-bold text-slate-900">{{ $category->name }}</td>
                            <td class="py-4 px-4 text-xs text-slate-500">{{ $category->parent->name ?? 'اصلی' }}</td>
                            <td class="py-4 px-4">
                                <span class="bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-lg text-xs font-semibold">
                                    {{ $category->products->count() }} کالا
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('آیا از حذف مطمئن هستید؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition">🗑️</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="p-4 border-t border-slate-100">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection

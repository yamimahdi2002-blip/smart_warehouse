@extends('layouts.app')

@section('title', 'موجودی انبارها و هشدارها')

@section('content')
    <div class="space-y-6">

        <!-- سربرگ و فیلترها -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h1 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">📦</span>
                    گزارش موجودی کالاها در انبارها
                </h1>
                <p class="text-xs text-slate-500 mt-1">مشاهده لحظه‌ای موجودی و کالاهای در آستانه اتمام</p>
            </div>

            <form method="GET" action="{{ route('stocks.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <select name="warehouse_id" onchange="this.form.submit()" class="px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                    <option value="">همه انبارهای مجاز</option>
                    @foreach($warehouses as $wh)
                        <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                            {{ $wh->name }}
                        </option>
                    @endforeach
                </select>

                <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 bg-rose-50 border border-rose-200 px-3 py-2 rounded-xl cursor-pointer">
                    <input type="checkbox" name="low_stock" value="1" onchange="this.form.submit()" {{ request('low_stock') ? 'checked' : '' }} class="rounded text-rose-600">
                    ⚠️ فقط کالاهای دارای کسری/هشدار
                </label>

                @if(request()->hasAny(['warehouse_id', 'low_stock']))
                    <a href="{{ route('stocks.index') }}" class="text-xs text-slate-500 hover:text-slate-800">حذف فیلترها</a>
                @endif
            </form>
        </div>

        <!-- جدول موجودی -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <table class="w-full text-right text-sm">
                <thead>
                <tr class="text-xs font-semibold text-slate-400 border-b border-slate-100 bg-slate-50/50">
                    <th class="py-3.5 px-4">کالا</th>
                    <th class="py-3.5 px-4">بارکد</th>
                    <th class="py-3.5 px-4">انبار</th>
                    <th class="py-3.5 px-4">موجودی فعلی</th>
                    <th class="py-3.5 px-4">نقطه سفارش (حداقل)</th>
                    <th class="py-3.5 px-4 text-center">وضعیت</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse($stocks as $stock)
                    @php
                        $isLowStock = $stock->quantity <= ($stock->product->reorder_level ?? 0);
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition {{ $isLowStock ? 'bg-rose-50/40' : '' }}">
                        <td class="py-4 px-4 font-bold text-slate-900">
                            {{ $stock->product->name ?? '-' }}
                            <span class="block text-xs font-normal text-slate-400 mt-0.5">{{ $stock->product->category->name ?? 'بدون دسته' }}</span>
                        </td>
                        <td class="py-4 px-4 font-mono text-xs text-slate-500">{{ $stock->product->barcode ?? '-' }}</td>
                        <td class="py-4 px-4 font-semibold text-slate-700">{{ $stock->warehouse->name ?? '-' }}</td>
                        <td class="py-4 px-4 font-bold text-base {{ $isLowStock ? 'text-rose-600' : 'text-slate-800' }}">
                            {{ number_format($stock->quantity) }}
                        </td>
                        <td class="py-4 px-4 font-mono text-xs text-slate-500">
                            {{ number_format($stock->product->reorder_level ?? 0) }}
                        </td>
                        <td class="py-4 px-4 text-center">
                            @if($isLowStock)
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-rose-100 text-rose-700 border border-rose-200">
                                    ⚠️ نیازمند شارژ
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    ✅ مطلوب
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 text-xs">هیچ داده‌ای یافت نشد.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="p-4 border-t border-slate-100">
                {{ $stocks->links() }}
            </div>
        </div>

    </div>
@endsection

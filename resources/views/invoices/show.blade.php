@extends('layouts.app')

@section('title', 'جزئیات فاکتور ' . $invoice->invoice_number)

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- سربرگ -->
        <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">📄</span>
                    فاکتور شماره: <span class="font-mono text-indigo-600">{{ $invoice->invoice_number }}</span>
                </h1>
                <p class="text-xs text-slate-500 mt-1">
                    ثبت شده در تاریخ {{ $invoice->created_at->format('Y/m/d - H:i') }}
                </p>
            </div>
            <a href="{{ route('invoices.index') }}" class="text-xs text-slate-500 hover:text-slate-800">
                ← بازگشت به لیست فاکتورها
            </a>
        </div>

        <!-- اطلاعات کلی فاکتور -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div>
                <span class="block text-xs font-semibold text-slate-400 mb-1">نوع فاکتور</span>
                @if($invoice->type === 'purchase')
                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">
                    📥 خرید (ورود به انبار)
                </span>
                @else
                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-lg bg-rose-50 text-rose-700 border border-rose-200">
                    📤 فروش (خروج از انبار)
                </span>
                @endif
            </div>

            <div>
                <span class="block text-xs font-semibold text-slate-400 mb-1">انبار مربوطه</span>
                <span class="text-sm font-bold text-slate-800">{{ $invoice->warehouse->name ?? '-' }}</span>
            </div>

            <div>
                <span class="block text-xs font-semibold text-slate-400 mb-1">تامین‌کننده / طرف حساب</span>
                <span class="text-sm font-bold text-slate-800">
                {{ $invoice->supplier->company_name ?? 'مشتری متفرقه' }}
            </span>
            </div>
        </div>

        <!-- جدول اقلام فاکتور -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-sm font-bold text-slate-800">اقلام ثبت‌شده در فاکتور</h3>
            </div>
            <table class="w-full text-right text-sm">
                <thead>
                <tr class="text-xs font-semibold text-slate-400 border-b border-slate-100">
                    <th class="py-3.5 px-4">ردیف</th>
                    <th class="py-3.5 px-4">نام کالا</th>
                    <th class="py-3.5 px-4">کد بارکد</th>
                    <th class="py-3.5 px-4">تعداد</th>
                    <th class="py-3.5 px-4">قیمت واحد</th>
                    <th class="py-3.5 px-4">جمع کل</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                @foreach($invoice->items as $index => $item)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-4 font-mono text-xs text-slate-400">{{ $index + 1 }}</td>
                        <td class="py-4 px-4 font-bold text-slate-900">{{ $item->product->name ?? '-' }}</td>
                        <td class="py-4 px-4 font-mono text-xs text-slate-500">{{ $item->product->barcode ?? '-' }}</td>
                        <td class="py-4 px-4 font-bold">{{ number_format($item->quantity) }}</td>
                        <td class="py-4 px-4 font-mono text-xs">{{ number_format($item->unit_price) }} تومان</td>
                        <td class="py-4 px-4 font-bold text-slate-900">{{ number_format($item->subtotal) }} تومان</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- مجموع فاکتور -->
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <span class="text-sm font-bold text-slate-700">مبلغ کل فاکتور:</span>
                <span class="text-lg font-black text-indigo-600">{{ number_format($invoice->total_amount) }} تومان</span>
            </div>
        </div>

    </div>
@endsection

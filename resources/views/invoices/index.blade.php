@extends('layouts.app')

@section('title', 'مدیریت فاکتورها')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">📄</span>
                    لیست فاکتورها (خرید و فروش)
                </h1>
                <p class="text-xs text-slate-500 mt-1">مدیریت تراکنش‌های ورود و خروج کالا از انبارها</p>
            </div>
            <a href="{{ route('invoices.create') }}" class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-xl shadow-lg shadow-indigo-600/20 transition">
                <span>+</span> ثبت فاکتور جدید
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm p-4 rounded-xl">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <table class="w-full text-right text-sm">
                <thead>
                <tr class="text-xs font-semibold text-slate-400 border-b border-slate-100 bg-slate-50/50">
                    <th class="py-3.5 px-4">شماره فاکتور</th>
                    <th class="py-3.5 px-4">نوع فاکتور</th>
                    <th class="py-3.5 px-4">انبار مربوطه</th>
                    <th class="py-3.5 px-4">تامین‌کننده / مشتری</th>
                    <th class="py-3.5 px-4">مبلغ کل</th>
                    <th class="py-3.5 px-4 text-center">جزئیات</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                @foreach($invoices as $invoice)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-4 font-mono font-bold text-slate-900">{{ $invoice->invoice_number }}</td>
                        <td class="py-4 px-4">
                            @if($invoice->type === 'purchase')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">ورود (خرید)</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-rose-50 text-rose-700 border border-rose-200">خروج (فروش)</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 font-medium">{{ $invoice->warehouse->name ?? '-' }}</td>
                        <td class="py-4 px-4 text-xs">{{ $invoice->supplier->company_name ?? 'مشتری متفرقه' }}</td>
                        <td class="py-4 px-4 font-bold text-slate-900">{{ number_format($invoice->total_amount) }} تومان</td>
                        <td class="py-4 px-4 text-center">
                            <a href="{{ route('invoices.show', $invoice->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">👁️ مشاهده</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'داشبورد مدیریتی')

@section('content')
    <div class="space-y-6">

        <!-- خوش‌آمدگویی و مشخصات کاربر -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl text-2xl">📊</div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900">سلام، {{ auth()->user()->name }} 👋</h1>
                    <p class="text-xs text-slate-500 mt-1">خوش آمدید! خلاصه وضعیت سامانه و تراکنش‌ها به شرح زیر است:</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-mono font-bold">
                کد پرسنلی: {{ auth()->user()->personnel_code }}
            </span>
                <span class="px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 text-xs font-bold">
                نقش: {{ auth()->user()->role->name ?? 'کاربر' }}
            </span>
            </div>
        </div>

        <!-- کارت‌های آماری اصلی -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-400 block">تعداد کل کالاها</span>
                    <span class="text-xl font-bold text-slate-900 mt-1 block">{{ number_format($totalProducts) }}</span>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl text-xl">📦</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-400 block">هشدار کسر موجودی</span>
                    <span class="text-xl font-bold text-rose-600 mt-1 block">{{ number_format($lowStockCount) }}</span>
                </div>
                <div class="p-3 bg-rose-50 text-rose-600 rounded-xl text-xl">⚠️</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-400 block">مجموع خریدها</span>
                    <span class="text-base font-bold text-slate-800 mt-1 block">{{ number_format($totalPurchaseAmount) }} <span class="text-[10px] font-normal text-slate-400">تومان</span></span>
                </div>
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl text-xl">📥</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-400 block">مجموع فروش‌ها</span>
                    <span class="text-base font-bold text-emerald-600 mt-1 block">{{ number_format($totalSaleAmount) }} <span class="text-[10px] font-normal text-slate-400">تومان</span></span>
                </div>
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl text-xl">📤</div>
            </div>

        </div>

        <!-- بخش گزارشات مالی (مخصوص مدیر ارشد و ناظر مالی) -->
        @if(in_array(auth()->user()->role->name, ['مدیر ارشد', 'ناظر مالی']))
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span>💰</span> تحلیل تراز مالی و درآمد
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">گزارش خلاصه سود و زیان حاصل از فاکتورهای ثبت شده</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-bold rounded-lg {{ $netProfit >= 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                    تراز کل: {{ number_format($netProfit) }} تومان
                </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                        <span class="text-xs text-slate-500 font-semibold block">ارزش فاکتورهای ورودی (خرید)</span>
                        <span class="text-sm font-bold text-slate-800 font-mono mt-1 block">{{ number_format($totalPurchaseAmount) }} تومان</span>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                        <span class="text-xs text-slate-500 font-semibold block">ارزش فاکتورهای خروجی (فروش)</span>
                        <span class="text-sm font-bold text-emerald-600 font-mono mt-1 block">{{ number_format($totalSaleAmount) }} تومان</span>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                        <span class="text-xs text-slate-500 font-semibold block">خالص گردش مالی</span>
                        <span class="text-sm font-bold {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-mono mt-1 block">
                        {{ number_format($netProfit) }} تومان
                    </span>
                    </div>
                </div>
            </div>
        @endif

        <!-- جدول آخرین فاکتورها -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span>📑</span> آخرین فاکتورهای ثبت‌شده
                </h2>
                <a href="{{ route('invoices.index') }}" class="text-xs text-indigo-600 font-semibold hover:underline">مشاهده همه فاکتورها ←</a>
            </div>

            <table class="w-full text-right text-sm">
                <thead>
                <tr class="text-xs font-semibold text-slate-400 border-b border-slate-100 bg-slate-50/50">
                    <th class="py-3.5 px-4">شماره فاکتور</th>
                    <th class="py-3.5 px-4">نوع</th>
                    <th class="py-3.5 px-4">انبار</th>
                    <th class="py-3.5 px-4">مبلغ کل</th>
                    <th class="py-3.5 px-4">ثبت‌کننده</th>
                    <th class="py-3.5 px-4 text-center">تاریخ</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse($recentInvoices as $inv)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-4 font-mono font-bold text-slate-900">{{ $inv->invoice_number }}</td>
                        <td class="py-3.5 px-4">
                            @if($inv->type === 'purchase')
                                <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-amber-50 text-amber-700 border border-amber-200">📥 خرید</span>
                            @else
                                <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">📤 فروش</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-xs font-semibold">{{ $inv->warehouse->name ?? '-' }}</td>
                        <td class="py-3.5 px-4 font-mono text-xs font-bold text-slate-800">{{ number_format($inv->total_amount) }} تومان</td>
                        <td class="py-3.5 px-4 text-xs text-slate-500">{{ $inv->user->name ?? '-' }}</td>
                        <td class="py-3.5 px-4 text-center font-mono text-xs text-slate-400">{{ $inv->created_at->format('Y/m/d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 text-xs">هیچ فاکتوری ثبت نشده است.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection

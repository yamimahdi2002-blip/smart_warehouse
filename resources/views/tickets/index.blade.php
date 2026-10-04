@extends('layouts.app')

@section('title', 'سیستم پشتیبانی و تیکت‌ها')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">💬</span>
                    تیکت‌های پشتیبانی
                </h1>
                <p class="text-xs text-slate-500 mt-1">ارسال و پیگیری پیام‌ها بر اساس کد پرسنلی</p>
            </div>
            <a href="{{ route('tickets.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium px-4 py-2.5 rounded-xl shadow-lg shadow-indigo-600/20 transition">
                + ثبت تیکت جدید
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
                    <th class="py-3.5 px-4">عنوان تیکت</th>
                    <th class="py-3.5 px-4">فرستنده</th>
                    <th class="py-3.5 px-4">گیرنده (کد پرسنلی)</th>
                    <th class="py-3.5 px-4">تاریخ ارسال</th>
                    <th class="py-3.5 px-4 text-center">وضعیت</th>
                    <th class="py-3.5 px-4 text-center">عملیات</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse($tickets as $ticket)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-4 font-bold text-slate-900">{{ $ticket->subject }}</td>
                        <td class="py-4 px-4 text-xs">
                            <span class="font-semibold block text-slate-800">{{ $ticket->sender->name ?? '-' }}</span>
                            <span class="text-slate-400 font-mono">({{ $ticket->sender->personnel_code ?? '-' }})</span>
                        </td>
                        <td class="py-4 px-4 text-xs">
                            <span class="font-semibold block text-slate-800">{{ $ticket->receiver->name ?? '-' }}</span>
                            <span class="text-slate-400 font-mono">({{ $ticket->receiver->personnel_code ?? '-' }})</span>
                        </td>
                        <td class="py-4 px-4 text-xs text-slate-500 font-mono">{{ $ticket->created_at->format('Y/m/d H:i') }}</td>
                        <td class="py-4 px-4 text-center">
                            @switch($ticket->status)
                                @case('pending')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-amber-50 text-amber-700 border border-amber-200">در انتظار بررسی</span>
                                    @break
                                @case('in_progress')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-blue-50 text-blue-700 border border-blue-200">در حال بررسی</span>
                                    @break
                                @case('resolved')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">حل‌شده</span>
                                    @break
                                @case('closed')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-slate-100 text-slate-600 border border-slate-200">بسته شده</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="py-4 px-4 text-center">
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition text-xs font-bold">
                                مشاهده 👁️
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 text-xs">هیچ تیکتی یافت نشد.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
@endsection

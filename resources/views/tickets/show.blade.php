@extends('layouts.app')

@section('title', 'مشاهده تیکت')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-lg font-bold text-slate-900">{{ $ticket->subject }}</h1>
                <p class="text-xs text-slate-500 mt-1">
                    ارسال شده توسط: <span class="font-bold text-slate-700">{{ $ticket->sender->name }}</span> (کد پرسنلی: {{ $ticket->sender->personnel_code }})
                </p>
            </div>
            <a href="{{ route('tickets.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← بازگشت</a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm p-4 rounded-xl">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
                <div class="flex items-center justify-between text-xs text-slate-500">
                    <span>گیرنده: <strong class="text-slate-800">{{ $ticket->receiver->name }}</strong> ({{ $ticket->receiver->personnel_code }})</span>
                    <span class="font-mono">{{ $ticket->created_at->format('Y/m/d - H:i') }}</span>
                </div>
                <p class="text-sm text-slate-800 leading-relaxed pt-2 border-t border-slate-200/60">
                    {{ $ticket->message }}
                </p>
            </div>

            <!-- فرم تغییر وضعیت (فقط برای گیرنده یا مدیر) -->
            @if(auth()->id() === $ticket->receiver_id || auth()->user()->role->name === 'مدیر ارشد')
                <form action="{{ route('tickets.status', $ticket->id) }}" method="POST" class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    @csrf
                    @method('PATCH')
                    <label class="text-xs font-semibold text-slate-700">تغییر وضعیت تیکت:</label>
                    <div class="flex items-center gap-2">
                        <select name="status" class="px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                            <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>در انتظار بررسی</option>
                            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>در حال بررسی</option>
                            <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>حل‌شده</option>
                            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>بسته شده</option>
                        </select>
                        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white text-xs font-medium px-4 py-2 rounded-xl">بروزرسانی</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection

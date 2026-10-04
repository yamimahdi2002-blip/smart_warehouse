@extends('layouts.app')

@section('title', 'ارسال تیکت جدید')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <h1 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">✉️</span>
                ثبت تیکت جدید
            </h1>
            <a href="{{ route('tickets.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← بازگشت</a>
        </div>

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs p-4 rounded-xl space-y-1">
                @foreach($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('tickets.store') }}" method="POST" class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">کد پرسنلی گیرنده <span class="text-rose-500">*</span></label>
                <input type="text" name="receiver_personnel_code" value="{{ old('receiver_personnel_code') }}" required placeholder="مثال: 1001"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">عنوان تیکت <span class="text-rose-500">*</span></label>
                <input type="text" name="subject" value="{{ old('subject') }}" required placeholder="موضوع درخواست یا گزارش خطا..."
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">متن پیام <span class="text-rose-500">*</span></label>
                <textarea name="message" rows="5" required placeholder="توضیحات کامل درخواست خود را بنویسید..."
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('message') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('tickets.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs">انصراف</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-600/20 transition">
                    ارسال تیکت
                </button>
            </div>
        </form>
    </div>
@endsection

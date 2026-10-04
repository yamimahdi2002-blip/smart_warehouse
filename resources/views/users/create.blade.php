@extends('layouts.app')

@section('title', 'تعریف کاربر جدید')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">

        <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <h1 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">👤</span>
                تعریف کاربر و پرسنل جدید
            </h1>
            <a href="{{ route('users.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← بازگشت</a>
        </div>

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 text-xs p-4 rounded-xl space-y-1">
                @foreach($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST" class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">نام و نام خانوادگی <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="مثال: علی محمدی"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">کد پرسنلی <span class="text-rose-500">*</span></label>
                    <input type="text" name="personnel_code" value="{{ old('personnel_code') }}" required placeholder="مثال: 1002"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">آدرس ایمیل <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="example@domain.com"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">رمز عبور <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" required placeholder="حداقل ۶ کاراکتر"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">نقش کاربر در سامانه <span class="text-rose-500">*</span></label>
                <select name="role_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                    <option value="">-- انتخاب نقش --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- انتخاب انبارها (برای تخصیص به انباردار) -->
            <div class="pt-2 border-t border-slate-100">
                <label class="block text-xs font-bold text-slate-800 mb-2">تخصیص انبارها (مخصوص نقش انباردار):</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 bg-slate-50 p-4 rounded-xl border border-slate-200">
                    @foreach($warehouses as $wh)
                        <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                            <input type="checkbox" name="warehouse_ids[]" value="{{ $wh->id }}"
                                   {{ is_array(old('warehouse_ids')) && in_array($wh->id, old('warehouse_ids')) ? 'checked' : '' }}
                                   class="rounded text-indigo-600 focus:ring-indigo-500">
                            🏢 {{ $wh->name }}
                        </label>
                    @endforeach
                </div>
                <p class="text-[11px] text-slate-400 mt-1">انباردار فقط فاکتورها و موجودی انبار‌های انتخاب‌شده را خواهد دید.</p>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('users.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs">انصراف</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-600/20 transition">
                    ذخیره کاربر
                </button>
            </div>
        </form>

    </div>
@endsection

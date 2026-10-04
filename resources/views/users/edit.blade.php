@extends('layouts.app')

@section('title', 'ویرایش کاربر')

@section('content')
    <div class="max-w-xl mx-auto space-y-6">

        <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <h1 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">✏️</span>
                ویرایش کاربر: {{ $user->name }}
            </h1>
            <a href="{{ route('users.index') }}" class="text-xs text-slate-500 hover:text-slate-800">
                ← بازگشت به لیست کاربران
            </a>
        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">نام و نام خانوادگی <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">کد پرسنلی <span class="text-rose-500">*</span></label>
                <input type="text" name="personnel_code" value="{{ old('personnel_code', $user->personnel_code) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none font-mono">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">پست الکترونیکی (ایمیل) <span class="text-rose-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none dir-ltr text-right">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">نقش کاربر در سیستم <span class="text-rose-500">*</span></label>
                <select name="role_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">رمز عبور جدید (در صورت تمایل به تغییر)</label>
                <input type="password" name="password" placeholder="تنها در صورت نیاز به تغییر وارد کنید"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('users.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs">انصراف</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-600/20">ذخیره تغییرات</button>
            </div>
        </form>

    </div>
@endsection

@extends('layouts.app')

@section('title', 'مدیریت کاربران و پرسنل')

@section('content')
    <div class="space-y-6">

        <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">👥</span>
                    مدیریت کاربران و پرسنل
                </h1>
                <p class="text-xs text-slate-500 mt-1">تعریف کاربران، تعیین سطح دسترسی و تخصیص انبار به پرسنل</p>
            </div>
            <a href="{{ route('users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium px-4 py-2.5 rounded-xl shadow-lg shadow-indigo-600/20 transition">
                + تعریف کاربر جدید
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm p-4 rounded-xl">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 text-sm p-4 rounded-xl">
                ❌ {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <table class="w-full text-right text-sm">
                <thead>
                <tr class="text-xs font-semibold text-slate-400 border-b border-slate-100 bg-slate-50/50">
                    <th class="py-3.5 px-4">نام و نام خانوادگی</th>
                    <th class="py-3.5 px-4">کد پرسنلی</th>
                    <th class="py-3.5 px-4">ایمیل</th>
                    <th class="py-3.5 px-4">نقش کاربر</th>
                    <th class="py-3.5 px-4">انبارهای مجاز</th>
                    <th class="py-3.5 px-4 text-center">عملیات</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse($users as $usr)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-4 font-bold text-slate-900">{{ $usr->name }}</td>
                        <td class="py-4 px-4 font-mono text-xs font-bold text-indigo-600">{{ $usr->personnel_code }}</td>
                        <td class="py-4 px-4 font-mono text-xs text-slate-500">{{ $usr->email }}</td>
                        <td class="py-4 px-4">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $usr->role->name ?? '-' }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-xs">
                            @if($usr->role && $usr->role->name === 'انباردار')
                                @forelse($usr->warehouses as $wh)
                                    <span class="inline-block bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md text-[11px] mb-1 font-semibold">
                                        🏢 {{ $wh->name }}
                                    </span>
                                @empty
                                    <span class="text-rose-500 text-[11px]">بدون انبار تخصیص‌یافته</span>
                                @endforelse
                            @else
                                <span class="text-slate-400 text-[11px]">دسترسی به کل سامانه</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center flex items-center justify-center gap-2">
                            <a href="{{ route('users.edit', $usr->id) }}" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition text-xs font-bold">
                                ✏️ ویرایش
                            </a>
                            @if($usr->id !== auth()->id())
                                <form action="{{ route('users.destroy', $usr->id) }}" method="POST" onsubmit="return confirm('آیا از حذف این کاربر اطمینان دارید؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition text-xs font-bold">
                                        ❌ حذف
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 text-xs">هیچ کاربری یافت نشد.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>

    </div>
@endsection

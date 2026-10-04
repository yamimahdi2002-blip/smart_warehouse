@extends('layouts.app')

@section('title', 'مدیریت تامین‌کنندگان')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">🏭</span>
                    لیست تامین‌کنندگان
                </h1>
                <p class="text-xs text-slate-500 mt-1">مدیریت طرف‌حساب‌ها و فروشندگان کالا</p>
            </div>
            <a href="{{ route('suppliers.create') }}" class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-xl shadow-lg shadow-indigo-600/20 transition">
                <span>+</span> ثبت تامین‌کننده جدید
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
                    <th class="py-3.5 px-4">نام تامین‌کننده / شرکت</th>
                    <th class="py-3.5 px-4">رابط</th>
                    <th class="py-3.5 px-4">شماره تماس</th>
                    <th class="py-3.5 px-4">ایمیل</th>
                    <th class="py-3.5 px-4">آدرس</th>
                    <th class="py-3.5 px-4 text-center">عملیات</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                @foreach($suppliers as $supplier)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-4 px-4 font-bold text-slate-900">{{ $supplier->company_name }}</td>
                        <td class="py-4 px-4 text-xs">{{ $supplier->contact_person ?? '-' }}</td>
                        <td class="py-4 px-4 font-mono text-xs">{{ $supplier->phone ?? '-' }}</td>
                        <td class="py-4 px-4 text-xs">{{ $supplier->email ?? '-' }}</td>
                        <td class="py-4 px-4 text-xs text-slate-500">{{ $supplier->address ?? '-' }}</td>
                        <td class="py-4 px-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="ویرایش">✏️</a>
                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('آیا از حذف مطمئن هستید؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="p-4 border-t border-slate-100">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
@endsection

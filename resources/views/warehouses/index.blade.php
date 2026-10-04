@extends('layouts.app')

@section('title', 'مدیریت انبارها')

@section('content')
    <div class="space-y-6">

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">🏭</span>
                    لیست انبارهای سیستم
                </h1>
                <p class="text-xs text-slate-500 mt-1">مدیریت انبارها و تعریف مسئولین و انبارداران مجاز</p>
            </div>
            <a href="{{ route('warehouses.create') }}" class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-4 py-2.5 rounded-xl shadow-lg shadow-indigo-600/20 transition duration-200">
                <span>+</span> تعریف انبار جدید
            </a>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm p-4 rounded-xl flex items-center gap-2">
                <span>✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            @if($warehouses->isEmpty())
                <div class="text-center py-12 text-slate-500 text-sm">
                    هنوز هیچ انباری در سیستم ثبت نشده است.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-sm">
                        <thead>
                        <tr class="text-xs font-semibold text-slate-400 border-b border-slate-100 bg-slate-50/50">
                            <th class="py-3.5 px-4">عنوان انبار</th>
                            <th class="py-3.5 px-4">موقعیت / آدرس</th>
                            <th class="py-3.5 px-4">انبارداران مسئول</th>
                            <th class="py-3.5 px-4">توضیحات</th>
                            <th class="py-3.5 px-4 text-center">عملیات</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($warehouses as $warehouse)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-4 font-bold text-slate-900">{{ $warehouse->name }}</td>
                                <td class="py-4 px-4 text-slate-600">{{ $warehouse->location ?? '-' }}</td>
                                <td class="py-4 px-4">
                                    @forelse($warehouse->users as $keeper)
                                        <span class="inline-block bg-slate-100 text-slate-700 text-xs px-2.5 py-1 rounded-lg border border-slate-200 mb-1">
                                            👤 {{ $keeper->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">تعیین نشده</span>
                                    @endforelse
                                </td>
                                <td class="py-4 px-4 text-slate-500 text-xs max-w-xs truncate">{{ $warehouse->description ?? '-' }}</td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="ویرایش">
                                            ✏️
                                        </a>
                                        <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST" onsubmit="return confirm('آیا از حذف این انبار اطمینان دارید؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="حذف">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100">
                    {{ $warehouses->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection

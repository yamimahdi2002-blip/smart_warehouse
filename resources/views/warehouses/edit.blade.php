@extends('layouts.app')

@section('title', 'ویرایش انبار')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">

        <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <h1 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">✏️</span>
                ویرایش انبار: {{ $warehouse->name }}
            </h1>
            <a href="{{ route('warehouses.index') }}" class="text-xs text-slate-500 hover:text-slate-800">
                ← بازگشت به لیست انبارها
            </a>
        </div>

        <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST" class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">نام انبار <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $warehouse->name) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">
                @error('name')
                <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">موقعیت مکانی / آدرس</label>
                <input type="text" name="location" value="{{ old('location', $warehouse->location) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">تخصیص انبارداران مسئول</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 bg-slate-50 p-4 rounded-xl border border-slate-200 max-h-48 overflow-y-auto">
                    @forelse($keepers as $keeper)
                        <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer bg-white p-2.5 rounded-lg border border-slate-200 hover:border-indigo-300 transition">
                            <input type="checkbox" name="keeper_ids[]" value="{{ $keeper->id }}"
                                   {{ in_array($keeper->id, $assignedKeepers) ? 'checked' : '' }}
                                   class="rounded text-indigo-600 focus:ring-indigo-500">
                            <span>{{ $keeper->name }} ({{ $keeper->personnel_code }})</span>
                        </label>
                    @empty
                        <p class="text-xs text-slate-400 col-span-2 text-center">هنوز هیچ کاربری با نقش انباردار تعریف نشده است.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">توضیحات تکمیلی</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">{{ old('description', $warehouse->description) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('warehouses.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 text-xs font-medium transition">
                    انصراف
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-600/20 transition">
                    ذخیره تغییرات
                </button>
            </div>
        </form>

    </div>
@endsection

@extends('layouts.app')

@section('title', 'تعریف کالای جدید')

@section('content')
    <!-- کتابخانه صنعتی ZXing برای اسکن بارکدهای خطی کالا -->
    <script src="https://unpkg.com/@zxing/library@latest" type="text/javascript"></script>

    <div class="max-w-2xl mx-auto space-y-6">

        <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <h1 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">📦</span>
                تعریف کالای جدید
            </h1>
            <a href="{{ route('products.index') }}" class="text-xs text-slate-500 hover:text-slate-800">
                ← بازگشت به لیست کالاها
            </a>
        </div>

        <form action="{{ route('products.store') }}" method="POST" class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">نام کالا <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="مثال: روغن موتور 4 لیتری"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">دسته‌بندی <span class="text-rose-500">*</span></label>
                    <select name="category_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                        <option value="">انتخاب کنید...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- فیلد بارکد + دکمه باز کردن دوربین -->
                <div class="sm:col-span-1">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">کد بارکد محصول <span class="text-rose-500">*</span></label>
                    <div class="flex gap-2">
                        <input type="text" id="barcode_input" name="barcode" value="{{ old('barcode') }}" required placeholder="اسکن یا تایپ کنید..."
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono focus:ring-2 focus:ring-indigo-500 outline-none">
                        <button type="button" onclick="startScanner()" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl border border-slate-300 transition shrink-0" title="اسکن با دوربین گوشی">
                            📷
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">واحد سنجش <span class="text-rose-500">*</span></label>
                    <input type="text" name="unit" value="{{ old('unit', 'عدد') }}" required placeholder="عدد، کیلوگرم..."
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">نقطه سفارش (حداقل موجودی) <span class="text-rose-500">*</span></label>
                    <input type="number" name="reorder_level" value="{{ old('reorder_level', 5) }}" required min="0"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
            </div>

            <!-- باکس نمایش دوربین (در حالت عادی مخفی است) -->
            <div id="scanner_container" class="hidden p-4 border border-indigo-200 bg-indigo-50/50 rounded-2xl text-center space-y-3">
                <div class="flex items-center justify-between text-xs font-semibold text-indigo-900">
                    <span>📷 خط قرمز را عمود بر خطوط بارکد بگیرید</span>
                    <button type="button" onclick="stopScanner()" class="text-rose-600 font-bold px-2 py-1 rounded hover:bg-rose-100">بستن ✕</button>
                </div>

                <div class="relative w-full max-w-sm h-60 bg-black rounded-xl overflow-hidden mx-auto border border-indigo-400">
                    <video id="barcode_video" class="w-full h-full object-cover"></video>
                    <!-- خط لیزر راهنما -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-4/5 h-0.5 bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,1)] animate-pulse"></div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('products.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs">انصراف</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-600/20">ثبت کالا</button>
            </div>
        </form>

    </div>

    <script>
        let codeReader = null;

        function playBeepSound() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.value = 1000;
                gain.gain.value = 0.1;
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.15);
            } catch (e) {}
        }

        function startScanner() {
            document.getElementById('scanner_container').classList.remove('hidden');

            if (!codeReader) {
                codeReader = new ZXing.BrowserBarcodeReader();
            }

            codeReader.decodeFromVideoDevice(undefined, 'barcode_video', (result, err) => {
                if (result) {
                    // ۱. قرارگیری کد خوانده شده در اینپوت
                    document.getElementById('barcode_input').value = result.text;

                    // ۲. پخش صدای بوق
                    playBeepSound();

                    // ۳. بستن دوربین
                    stopScanner();
                }
            }).catch(err => {
                alert("خطا در دسترسی به دوربین! مطمئن شوید که دسترسی صادر شده و آدرس صفحه به صورت HTTPS یا localhost است.");
                stopScanner();
            });
        }

        function stopScanner() {
            if (codeReader) {
                codeReader.reset();
            }
            document.getElementById('scanner_container').classList.add('hidden');
        }
    </script>
@endsection

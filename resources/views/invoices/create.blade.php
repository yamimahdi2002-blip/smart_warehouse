@extends('layouts.app')

@section('title', 'ثبت فاکتور جدید')

@section('content')
    <!-- کتابخانه صنعتی ZXing برای اسکن بارکدهای خطی کالا -->
    <script src="https://unpkg.com/@zxing/library@latest" type="text/javascript"></script>

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <h1 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">🧾</span>
                ثبت فاکتور جدید
            </h1>
            <a href="{{ route('invoices.index') }}" class="text-xs text-slate-500 hover:text-slate-800">← بازگشت</a>
        </div>

        <form action="{{ route('invoices.store') }}" method="POST" class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
            @csrf

            <!-- مشخصات سربرگ -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">نوع فاکتور <span class="text-rose-500">*</span></label>
                    <select name="type" id="invoice_type" onchange="toggleSupplier()" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="purchase">خرید (ورود به انبار)</option>
                        <option value="sale">فروش (خروج از انبار)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">شماره فاکتور <span class="text-rose-500">*</span></label>
                    <input type="text" name="invoice_number" value="INV-{{ time() }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm font-mono focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">انبار مقصد/مبدا <span class="text-rose-500">*</span></label>
                    <select name="warehouse_id" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="supplier_box">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">تامین‌کننده</label>
                    <select name="supplier_id" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                        <option value="">انتخاب کنید...</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->company_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- بخش اسکن بارکد سریع -->
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between gap-4">
                <div class="flex-1">
                    <input type="text" id="quick_barcode" placeholder="بارکد محصول را اسکن کنید یا بنویسید..." class="w-full px-4 py-2 rounded-xl border border-slate-300 text-sm font-mono outline-none" onkeydown="if(event.key==='Enter'){event.preventDefault(); findAndAddProduct(this.value);}">
                </div>
                <button type="button" onclick="startScanner()" class="px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold rounded-xl border border-indigo-200 text-xs transition flex items-center gap-2 shrink-0">
                    📷 اسکن با دوربین
                </button>
            </div>

            <!-- باکس نمایش دوربین اسکنر -->
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

            <!-- جدول اقلام فاکتور -->
            <div>
                <h3 class="text-sm font-bold text-slate-800 mb-3">اقلام فاکتور</h3>
                <table class="w-full text-right text-sm" id="items_table">
                    <thead>
                    <tr class="text-xs text-slate-400 border-b border-slate-200">
                        <th class="py-2">کالا</th>
                        <th class="py-2 w-32">تعداد</th>
                        <th class="py-2 w-40">قیمت واحد (تومان)</th>
                        <th class="py-2 w-20 text-center">حذف</th>
                    </tr>
                    </thead>
                    <tbody id="items_body">
                    <!-- ردیف‌ها به صورت پویا با JS اضافه می‌شوند -->
                    </tbody>
                </table>
                <button type="button" onclick="addRow()" class="mt-3 text-xs text-indigo-600 font-bold hover:underline">+ افزودن ردیف دستی</button>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('invoices.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs">انصراف</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-600/20">ثبت نهایی فاکتور</button>
            </div>
        </form>
    </div>

    <script>
        const products = @json($products);
        let itemIndex = 0;
        let codeReader = null;

        function toggleSupplier() {
            const type = document.getElementById('invoice_type').value;
            document.getElementById('supplier_box').style.display = type === 'purchase' ? 'block' : 'none';
        }

        function addRow(productId = '', quantity = 1, price = 0) {
            let options = '<option value="">انتخاب کالا...</option>';
            products.forEach(p => {
                options += `<option value="${p.id}" ${p.id == productId ? 'selected' : ''}>${p.name}</option>`;
            });

            const row = `
            <tr id="row_${itemIndex}" class="border-b border-slate-100">
                <td class="py-2">
                    <select name="items[${itemIndex}][product_id]" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs outline-none">
                        ${options}
                    </select>
                </td>
                <td class="py-2">
                    <input type="number" name="items[${itemIndex}][quantity]" value="${quantity}" min="1" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs outline-none">
                </td>
                <td class="py-2">
                    <input type="number" name="items[${itemIndex}][unit_price]" value="${price}" min="0" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs outline-none">
                </td>
                <td class="py-2 text-center">
                    <button type="button" onclick="removeRow(${itemIndex})" class="text-rose-600 hover:bg-rose-50 p-1 rounded-lg">🗑️</button>
                </td>
            </tr>
        `;
            document.getElementById('items_body').insertAdjacentHTML('beforeend', row);
            itemIndex++;
        }

        function removeRow(index) {
            document.getElementById(`row_${index}`).remove();
        }

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

        function findAndAddProduct(barcode) {
            if(!barcode) return;
            const product = products.find(p => p.barcode === barcode.trim());
            if(product) {
                addRow(product.id, 1, 0);
                document.getElementById('quick_barcode').value = '';
                playBeepSound();
            } else {
                alert('کالایی با این بارکد یافت نشد!');
            }
        }

        // اسکنر جدید با کتابخانه ZXing
        function startScanner() {
            document.getElementById('scanner_container').classList.remove('hidden');

            if (!codeReader) {
                codeReader = new ZXing.BrowserBarcodeReader();
            }

            codeReader.decodeFromVideoDevice(undefined, 'barcode_video', (result, err) => {
                if (result) {
                    findAndAddProduct(result.text);
                    stopScanner();
                }
            }).catch(err => {
                alert("خطا در دسترسی به دوربین! از برقراری اتصال HTTPS یا localhost مطمئن شوید.");
                stopScanner();
            });
        }

        function stopScanner() {
            if (codeReader) {
                codeReader.reset();
            }
            document.getElementById('scanner_container').classList.add('hidden');
        }

        // اضافه کردن یک ردیف اولیه
        addRow();
    </script>
@endsection

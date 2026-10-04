<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اسکنر بارکد خطی کالاها</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- کتابخانه استاندارد صنعتی ZXing -->
    <script type="text/javascript" src="https://unpkg.com/@zxing/library@latest"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">

<div class="w-full max-w-md bg-white rounded-3xl shadow-xl border border-slate-200/80 p-6 space-y-5">

    <div class="text-center space-y-1">
        <h2 class="text-base font-bold text-slate-900 flex items-center justify-center gap-2">
            <span>📷</span> اسکنر بارکد خطی اجناس
        </h2>
        <p class="text-xs text-slate-500">بارکد روی محصول (EAN-13 / Code128) را جلوی دوربین بگیرید</p>
    </div>

    <!-- کادر ویدیو و خط لیزر اسکن -->
    <div class="relative w-full h-64 bg-black rounded-2xl overflow-hidden shadow-inner border-2 border-indigo-500">
        <video id="video" class="w-full h-full object-cover"></video>

        <!-- خط راهنمای قرمز برای تراز کردن خطوط بارکد -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <div class="w-4/5 h-0.5 bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,1)] animate-pulse"></div>
        </div>

        <div class="absolute bottom-2 inset-x-0 text-center">
            <span class="bg-black/60 text-white text-[10px] px-3 py-1 rounded-full backdrop-blur-sm">
                خط قرمز را عمود بر خطوط بارکد تنظیم کنید
            </span>
        </div>
    </div>

    <!-- فیلد نمایش نتیجه -->
    <div class="space-y-1.5">
        <label class="block text-xs font-bold text-slate-700">کد اسکن شده:</label>
        <div class="flex gap-2">
            <input type="text" id="result" readonly placeholder="در انتظار اسکن..."
                   class="w-full px-4 py-3 rounded-xl border border-slate-300 font-mono text-center text-lg font-bold text-indigo-600 bg-slate-50 focus:outline-none">
        </div>
    </div>

    <button id="reset-btn" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition shadow-lg shadow-indigo-600/20">
        اسکن مجدد
    </button>

</div>

<script>
    window.addEventListener('load', function () {
        const codeReader = new ZXing.BrowserBarcodeReader();
        const videoElement = document.getElementById('video');
        const resultInput = document.getElementById('result');
        const resetBtn = document.getElementById('reset-btn');

        // تابع پخش صدای بوق موفقیت
        function playBeep() {
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

        // شروع اسکن هوشمند
        function startScanner() {
            codeReader.decodeFromVideoDevice(undefined, 'video', (result, err) => {
                if (result) {
                    // پخش بوق و قرار دادن عدد در اینپوت
                    playBeep();
                    resultInput.value = result.text;

                    // متوقف کردن اسکن پس از یافتن بارکد
                    codeReader.reset();
                }
            }).catch((err) => {
                console.error("خطا در دسترسی به دوربین:", err);
            });
        }

        startScanner();

        resetBtn.addEventListener('click', () => {
            resultInput.value = '';
            codeReader.reset();
            startScanner();
        });
    });
</script>

</body>
</html>

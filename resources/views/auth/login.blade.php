<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به سامانه انبارداری</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <style> body { font-family: 'Vazirmatn', sans-serif; } </style>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

<!-- پس‌زمینه گرافیکی -->
<div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
<div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>

<div class="max-w-md w-full bg-slate-800/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-slate-700/60 relative z-10">
    <div class="text-center mb-8">
        <div class="inline-flex p-3 bg-indigo-600/20 border border-indigo-500/30 rounded-2xl mb-3 text-3xl shadow-inner">📦</div>
        <h2 class="text-2xl font-bold text-white tracking-tight">ورود به حساب کاربری</h2>
        <p class="text-xs text-slate-400 mt-1.5">سامانه هوشمند مدیریت موجودی و انبارداری</p>
    </div>

    @if($errors->any())
        <div class="bg-rose-500/10 border border-rose-500/30 p-4 mb-6 rounded-2xl">
            <ul class="text-xs text-rose-300 space-y-1">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-slate-300 mb-2">پست الکترونیکی</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   placeholder="name@company.com"
                   class="w-full px-4 py-3 rounded-xl bg-slate-900/60 border border-slate-700 text-white placeholder-slate-500 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition duration-200 dir-ltr">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-300 mb-2">رمز عبور</label>
            <input type="password" name="password" required
                   placeholder="••••••••"
                   class="w-full px-4 py-3 rounded-xl bg-slate-900/60 border border-slate-700 text-white placeholder-slate-500 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition duration-200">
        </div>

        <div class="flex items-center justify-between text-xs">
            <label class="flex items-center text-slate-400 cursor-pointer">
                <input type="checkbox" name="remember" class="rounded border-slate-700 bg-slate-900 text-indigo-600 shadow-sm focus:ring-indigo-500 focus:ring-offset-slate-800">
                <span class="mr-2">مرا به خاطر بسپار</span>
            </label>
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-medium py-3 rounded-xl shadow-lg shadow-indigo-600/30 transition duration-200 text-sm">
            ورود به سامانه
        </button>
    </form>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'سامانه مدیریت انبار')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Vazirmatn Font -->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
    <style>
        body { font-family: 'Vazirmatn', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col antialiased">

<!-- Top Navbar -->
<header class="bg-slate-900 text-white shadow-lg sticky top-0 z-50 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-600 rounded-xl shadow-md text-xl">📦</div>
            <div>
                <h1 class="text-base font-bold tracking-tight text-white">سامانه هوشمند انبارداری</h1>
                <p class="text-xs text-slate-400">مدیریت متمرکز موجودی و زنجیره تامین</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="hidden sm:flex items-center gap-2 bg-slate-800/80 px-3.5 py-1.5 rounded-xl border border-slate-700/60 text-xs text-slate-200">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="font-medium">{{ auth()->user()->name }}</span>
                <span class="text-slate-400">|</span>
                <span class="text-indigo-300 font-semibold">{{ auth()->user()->role->name ?? 'کاربر' }}</span>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-white border border-rose-500/20 hover:border-transparent text-xs font-medium px-3.5 py-2 rounded-xl transition-all duration-200 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    خروج
                </button>
            </form>
        </div>
    </div>
</header>

<div class="flex flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 gap-8">
    <!-- Sidebar Menu -->
    <!-- Sidebar Menu -->
    <aside class="w-64 shrink-0">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sticky top-24 space-y-1">
            <div class="px-3 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">منوی کاربری</div>

            <!-- داشبورد (برای همه) -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-50/80 text-indigo-700 border border-indigo-100' : 'text-slate-600 hover:bg-slate-50' }} transition-all">
                <span class="text-lg">📊</span>
                داشبورد مدیریتی
            </a>

            <!-- موجودی انبارها (جدید - برای همه) -->
            <a href="{{ route('stocks.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('stocks.*') ? 'bg-indigo-50/80 text-indigo-700 border border-indigo-100' : 'text-slate-600 hover:bg-slate-50' }} transition-all">
                <span class="text-lg">📉</span>
                موجودی و هشدارها
            </a>

            <!-- فاکتورها (برای همه) -->
            <a href="{{ route('invoices.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('invoices.*') ? 'bg-indigo-50/80 text-indigo-700 border border-indigo-100' : 'text-slate-600 hover:bg-slate-50' }} transition-all">
                <span class="text-lg">🧾</span>
                مدیریت فاکتورها
            </a>

            <!-- پشتیبانی (جدید - برای همه) -->
            <a href="{{ route('tickets.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('tickets.*') ? 'bg-indigo-50/80 text-indigo-700 border border-indigo-100' : 'text-slate-600 hover:bg-slate-50' }} transition-all">
                <span class="text-lg">💬</span>
                پشتیبانی و تیکت‌ها
            </a>

            <!-- بخش کالاها (فقط برای مدیر و انباردار - ناظر مالی نمی‌بیند) -->
            @if(auth()->check() && auth()->user()->role->name !== 'ناظر مالی')
                <div class="px-3 py-2 mt-4 text-xs font-semibold text-slate-400 uppercase tracking-wider border-t border-slate-100 pt-4">عملیات انبار</div>

                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('categories.*') ? 'bg-indigo-50/80 text-indigo-700 border border-indigo-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span class="text-lg">📁</span>
                    دسته‌بندی کالاها
                </a>

                <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('products.*') ? 'bg-indigo-50/80 text-indigo-700 border border-indigo-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span class="text-lg">📦</span>
                    مدیریت کالاها
                </a>
            @endif

            <!-- مدیریت سیستم (فقط برای مدیر ارشد) -->
            @if(auth()->check() && auth()->user()->role->name === 'مدیر ارشد')
                <div class="px-3 py-2 mt-4 text-xs font-semibold text-slate-400 uppercase tracking-wider border-t border-slate-100 pt-4">مدیریت سیستم</div>

                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('users.*') ? 'bg-indigo-50/80 text-indigo-700 border border-indigo-100' : 'text-slate-600 hover:bg-slate-50' }} transition-all">
                    <span class="text-lg">👥</span>
                    کاربران و پرسنل
                </a>

                <a href="{{ route('warehouses.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium {{ request()->routeIs('warehouses.*') ? 'bg-indigo-50/80 text-indigo-700 border border-indigo-100' : 'text-slate-600 hover:bg-slate-50' }} transition-all">
                    <span class="text-lg">🏭</span>
                    مدیریت انبارها
                </a>

                <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('suppliers.*') ? 'bg-indigo-50/80 text-indigo-700 border border-indigo-100' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span class="text-lg">🚚</span>
                    تامین‌کنندگان
                </a>

            @endif

        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>
</div>

</body>
</html>

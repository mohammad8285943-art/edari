<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }

        .sidebar-transition {
            transition: width 0.25s ease, transform 0.25s ease;
        }

        .submenu-transition {
            transition: grid-template-rows 0.25s ease, opacity 0.2s ease;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 h-screen flex overflow-hidden"
    x-data="{ sidebarOpen: false, sidebarCollapsed: false, menus: { projects: false, orphan: true } }"
    @keydown.escape.window="sidebarOpen = false">
    <x-layout.sidebar></x-layout.sidebar>

    <div x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
        class="fixed inset-0 z-30 bg-gray-900/40 md:hidden"></div>

    <div class="flex-1 flex flex-col overflow-hidden w-full">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-30">
            <div class="flex items-center gap-4">
                <button type="button"
                    @click="window.innerWidth < 768 ? sidebarOpen = true : sidebarCollapsed = !sidebarCollapsed"
                    class="p-2 rounded-xl hover:bg-gray-100 text-gray-600 transition"
                    aria-label="تبديل القائمة الجانبية">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="text-sm hidden sm:block">
                    <span class="font-bold text-gray-900">{{ now()->locale('ar')->translatedFormat('l') }}</span>،
                    <span class="text-gray-500">{{ now()->locale('ar')->translatedFormat('d F Y') }}</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button class="p-2 rounded-xl hover:bg-gray-100 text-gray-500 transition relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                    <span class="absolute top-1.5 left-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <div class="w-px h-6 bg-gray-200 mx-1"></div>
                <a class="p-2 rounded-xl hover:bg-red-50 text-red-500 transition" href="{{ route('logout') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                </a>
            </div>
        </header>

        {{ $slot }}
    </div>
</body>

</html>

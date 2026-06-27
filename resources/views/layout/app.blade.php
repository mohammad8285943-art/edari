<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }

        .sidebar-transition {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* أنيميشن ناعم لفتح القوائم الفرعية */
        .submenu-transition {
            transition: max-height 0.3s ease-in-out, opacity 0.2s ease-in-out;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 h-screen flex overflow-hidden">
    <x-layout.sidebar></x-layout.sidebar>
    <div class="flex-1 flex flex-col overflow-hidden w-full">

        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-30">
            <div class="flex items-center gap-4">
                <button id="toggle-sidebar" class="p-2 rounded-xl hover:bg-gray-100 text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="text-sm hidden sm:block">
                    <span id="current-day" class="font-bold text-gray-900"></span>،
                    <span id="current-date" class="text-gray-500"></span>
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


            {{$slot}}

    </div>

    <script>
        const toggleBtn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');
        const logoText = document.getElementById('logo-text');
        const closeSidebarMobile = document.getElementById('close-sidebar-mobile');

        // 1. تشغيل وتفعيل القوائم المنسدلة الفرعية (Dropdown Submenus)
        const dropdownButtons = document.querySelectorAll('.dropdown-btn');

        dropdownButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const submenu = this.nextElementSibling;
                const chevron = this.querySelector('.chevron-icon');

                // في حال كان السايدبار منكمشاً (صغيراً)، نقوم بتوسيعه أولاً لرؤية العناصر الفرعية بوضوح
                if (sidebar.classList.contains('w-20')) {
                    expandSidebar();
                }

                if (submenu.style.maxHeight && submenu.style.maxHeight !== '0px') {
                    // إغلاق القائمة
                    submenu.style.maxHeight = '0px';
                    submenu.style.opacity = '0';
                    chevron.classList.remove('rotate-180');
                } else {
                    // فتح القائمة ديناميكياً بحسب طول محتواها الداخلي
                    submenu.style.maxHeight = submenu.scrollHeight + "px";
                    submenu.style.opacity = '1';
                    chevron.classList.add('rotate-180');
                }
            });
        });

        // دالة مساعدة لتوسيع السايدبار
        function expandSidebar() {
            sidebar.classList.replace('w-20', 'w-64');
            sidebarTexts.forEach(el => el.classList.replace('opacity-0', 'opacity-100'));
            logoText.classList.replace('opacity-0', 'opacity-100');
        }

        // 2. منطق زر تصغير وتكبير السايدبار الرئيسي
        toggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (window.innerWidth >= 768) {
                if (sidebar.classList.contains('w-64')) {
                    // تصغير السايدبار وإغلاق كل القوائم المفتوحة تلقائياً لمنع التشوه البصري
                    sidebar.classList.replace('w-64', 'w-20');
                    sidebarTexts.forEach(el => el.classList.replace('opacity-100', 'opacity-0'));
                    logoText.classList.replace('opacity-100', 'opacity-0');

                    document.querySelectorAll('.submenu').forEach(sub => {
                        sub.style.maxHeight = '0px';
                        sub.style.opacity = '0';
                    });
                    document.querySelectorAll('.chevron-icon').forEach(chv => chv.classList.remove('rotate-180'));
                } else {
                    expandSidebar();
                }
            } else {
                sidebar.classList.remove('translate-x-full'); // فتح في الموبايل
            }
        });

        closeSidebarMobile.addEventListener('click', () => {
            sidebar.classList.add('translate-x-full');
        });

        // 3. تحديث اليوم والتاريخ الحالي تلقائياً
        function updateDateTime() {
            const optionsDay = {
                weekday: 'long'
            };
            const optionsDate = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const today = new Date();
            document.getElementById('current-day').innerText = today.toLocaleDateString('ar-EG', optionsDay);
            document.getElementById('current-date').innerText = today.toLocaleDateString('ar-EG', optionsDate);
        }
        updateDateTime();
    </script>
</body>

</html>

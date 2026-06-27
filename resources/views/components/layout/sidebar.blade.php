
<aside id="sidebar" class="sidebar-transition w-64 bg-white border-l border-gray-200 flex flex-col justify-between fixed inset-y-0 right-0 z-40 transform translate-x-full md:translate-x-0 md:relative">
    <div class="overflow-y-auto flex-1">
        <div class="h-16 flex items-center justify-between px-4 border-b border-gray-100 sticky top-0 bg-white z-10">
            <div class="flex items-center gap-3 overflow-hidden whitespace-nowrap">
                <span class="text-2xl">🇵🇸</span>
                <span  class="sidebar-text font-bold text-lg text-emerald-600 opacity-100 transition-opacity duration-200  whitespace-nowrap flex-1">لوحة التحكم</span>
            </div>
            <button id="close-sidebar-mobile" class="md:hidden p-1 rounded-lg hover:bg-gray-100 text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <nav class="p-3 space-y-1">

            <a wire:navigate href="{{ route('main')}}" class="flex items-center gap-4 px-3 py-3 rounded-xl bg-emerald-50 text-emerald-700 font-medium group transition">
                <span class="text-xl">📊</span>
                <span class="sidebar-text opacity-100 transition-opacity duration-200 whitespace-nowrap flex-1">الرئيسية</span>
            </a>

            <div class="dropdown-container">
                <button class="dropdown-btn w-full flex items-center gap-4 px-3 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium transition focus:outline-none">
                    <span class="text-xl">🤝</span>
                    <span class="sidebar-text opacity-100 transition-opacity duration-200 whitespace-nowrap flex-1 text-right">المشاريع (رئيسي وفرعي)</span>
                    <svg class="chevron-icon w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div class="submenu submenu-transition max-h-0 opacity-0 overflow-hidden bg-gray-50/50 rounded-xl mr-2 mt-1 space-y-1">
                    <a href="#" class="block px-10 py-2 text-sm text-gray-600 hover:text-emerald-600 transition">سقيا الماء وحفر الآبار</a>
                    <a href="#" class="block px-10 py-2 text-sm text-gray-600 hover:text-emerald-600 transition">السلال الغذائية الطارئة</a>
                    <a href="#" class="block px-10 py-2 text-sm text-gray-600 hover:text-emerald-600 transition">ترميم البيوت والخيام</a>
                </div>
            </div>

            <div class="dropdown-container">
                <button class="dropdown-btn w-full flex items-center gap-4 px-3 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium transition focus:outline-none">
                    <span class="text-xl">👥</span>
                    <span class="sidebar-text opacity-100 transition-opacity duration-200 whitespace-nowrap flex-1 text-right">شؤون الكفالات</span>
                    <svg class="chevron-icon w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div class="submenu submenu-transition max-h-0 opacity-0 overflow-hidden bg-gray-50/50 rounded-xl mr-2 mt-1 space-y-1">
                    <a href="#" class="block px-10 py-2 text-sm text-gray-600 hover:text-emerald-600 transition">كفالات الأيتام</a>
                    <a href="#" class="block px-10 py-2 text-sm text-gray-600 hover:text-emerald-600 transition">كفالات الأسر العفيفة</a>
                </div>
            </div>

            <a href="{{ route('users')}}" wire:navigate class="flex items-center gap-4 px-3 py-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium transition">
                <span class="text-xl">👮‍♂️</span>
                <span class="sidebar-text opacity-100 transition-opacity duration-200 whitespace-nowrap flex-1">إدارة المستخدمين</span>
            </a>

        </nav>
    </div>

    <div class="p-3 border-t border-gray-100 bg-white sticky bottom-0">
        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 transition cursor-pointer">
            <div class="w-10 h-10 bg-emerald-600 text-white font-bold rounded-xl flex items-center justify-center shadow-sm">{{ auth()->user()->name[0] }}</div>
            <div class="sidebar-text overflow-hidden whitespace-nowrap">
                <h4 class="font-bold text-sm text-gray-900">{{ auth()->user()->name }}</h4>
                <p class="text-xs text-gray-400">{{ auth()->user()->role }}</p>
            </div>
        </div>
    </div>
</aside>

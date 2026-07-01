<aside id="sidebar"
    class="sidebar-transition fixed inset-y-0 right-0 z-40 flex flex-col justify-between border-l border-gray-200 bg-white shadow-2xl shadow-gray-900/10 md:relative md:translate-x-0 md:shadow-none"
    :class="{
        'translate-x-0 w-72': sidebarOpen,
        'translate-x-full w-72': !sidebarOpen,
        'md:w-20': sidebarCollapsed,
        'md:w-72': !sidebarCollapsed
    }">
    <div class="flex-1" :class="sidebarCollapsed ? 'md:overflow-hidden' : 'overflow-y-auto'">
        <div class="sticky top-0 z-10 flex h-16 items-center justify-between border-b border-gray-100 bg-white px-4">
            <div class="flex min-w-0 items-center gap-3">
                <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-emerald-600 text-white shadow-sm">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4M9 9h1m-1 4h1m4-4h1m-1 4h1">
                        </path>
                    </svg>
                </div>
                <div class="min-w-0 transition-all duration-200" :class="sidebarCollapsed ? 'md:w-0 md:opacity-0' : 'opacity-100'">
                    <h2 class="truncate text-base font-bold text-gray-900">لوحة التحكم</h2>
                    <p class="truncate text-xs text-gray-400">إدارة النظام</p>
                </div>
            </div>

            <button type="button" @click="sidebarOpen = false"
                class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 md:hidden" aria-label="إغلاق القائمة">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <nav class="space-y-1.5 p-3">
            <a wire:navigate href="{{ route('main') }}"
                class="group relative flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('main') ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-950' }}"
                :class="sidebarCollapsed ? 'md:justify-center' : ''">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l9-9 9 9M5 10v10h14V10M9 20v-6h6v6"></path>
                </svg>
                <span class="min-w-0 flex-1 truncate transition-all duration-200"
                    :class="sidebarCollapsed ? 'md:w-0 md:flex-none md:opacity-0' : 'opacity-100'">الرئيسية</span>
                <span x-show="sidebarCollapsed" x-cloak
                    class="pointer-events-none absolute right-full top-1/2 z-50 mr-3 hidden -translate-y-1/2 whitespace-nowrap rounded-lg bg-gray-900 px-2.5 py-1.5 text-xs text-white opacity-0 shadow-lg transition group-hover:opacity-100 md:block">
                    الرئيسية
                </span>
            </a>

            <div>
                <button type="button" @click="sidebarCollapsed ? sidebarCollapsed = false : menus.projects = !menus.projects"
                    class="group relative flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-gray-600 transition hover:bg-gray-50 hover:text-gray-950"
                    :class="sidebarCollapsed ? 'md:justify-center' : ''">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-6a2 2 0 012-2h8M9 17H5a2 2 0 01-2-2V7a2 2 0 012-2h8a2 2 0 012 2v2M9 17a2 2 0 002 2h8a2 2 0 002-2v-6">
                        </path>
                    </svg>
                    <span class="min-w-0 flex-1 truncate text-right transition-all duration-200"
                        :class="sidebarCollapsed ? 'md:w-0 md:flex-none md:opacity-0' : 'opacity-100'">المشاريع</span>
                    <svg class="h-4 w-4 shrink-0 transition-all duration-200"
                        :class="{ 'rotate-180': menus.projects, 'md:hidden': sidebarCollapsed }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                    <span x-show="sidebarCollapsed" x-cloak
                        class="pointer-events-none absolute right-full top-1/2 z-50 mr-3 hidden -translate-y-1/2 whitespace-nowrap rounded-lg bg-gray-900 px-2.5 py-1.5 text-xs text-white opacity-0 shadow-lg transition group-hover:opacity-100 md:block">
                        المشاريع
                    </span>
                </button>

                <div class="submenu-transition grid pr-5" :class="menus.projects && !sidebarCollapsed ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'">
                    <div class="overflow-hidden">
                        <div class="mt-1 space-y-1 border-r border-gray-100 pr-3">
                            <a href="#"
                                class="block rounded-lg px-3 py-2 text-sm text-gray-500 transition hover:bg-emerald-50 hover:text-emerald-700">سقيا الماء وحفر الآبار</a>
                            <a href="#"
                                class="block rounded-lg px-3 py-2 text-sm text-gray-500 transition hover:bg-emerald-50 hover:text-emerald-700">السلال الغذائية الطارئة</a>
                            <a href="#"
                                class="block rounded-lg px-3 py-2 text-sm text-gray-500 transition hover:bg-emerald-50 hover:text-emerald-700">ترميم البيوت والخيام</a>
                        </div>
                    </div>
                </div>
            </div>

            @can('orphan.view')
                <div>
                    <button type="button" @click="sidebarCollapsed ? sidebarCollapsed = false : menus.orphan = !menus.orphan"
                        class="group relative flex w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('orphan.*') ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-950' }}"
                        :class="sidebarCollapsed ? 'md:justify-center' : ''">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 100-8 4 4 0 000 8z">
                            </path>
                        </svg>
                        <span class="min-w-0 flex-1 truncate text-right transition-all duration-200"
                            :class="sidebarCollapsed ? 'md:w-0 md:flex-none md:opacity-0' : 'opacity-100'">ملف الأيتام والأرامل</span>
                        <svg class="h-4 w-4 shrink-0 transition-all duration-200"
                            :class="{ 'rotate-180': menus.orphan, 'md:hidden': sidebarCollapsed }" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                        <span x-show="sidebarCollapsed" x-cloak
                            class="pointer-events-none absolute right-full top-1/2 z-50 mr-3 hidden -translate-y-1/2 whitespace-nowrap rounded-lg bg-gray-900 px-2.5 py-1.5 text-xs text-white opacity-0 shadow-lg transition group-hover:opacity-100 md:block">
                            ملف الأيتام والأرامل
                        </span>
                    </button>

                    <div class="submenu-transition grid pr-5" :class="menus.orphan && !sidebarCollapsed ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'">
                        <div class="overflow-hidden">
                            <div class="mt-1 space-y-1 border-r border-gray-100 pr-3">
                                <a href="{{ route('orphan.index') }}" wire:navigate
                                    class="block rounded-lg px-3 py-2 text-sm transition {{ request()->routeIs('orphan.index') ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-500 hover:bg-emerald-50 hover:text-emerald-700' }}">الأيتام</a>
                                <a href="#" wire:navigate
                                    class="block rounded-lg px-3 py-2 text-sm text-gray-500 transition hover:bg-emerald-50 hover:text-emerald-700">الكفالات</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('usersAdmin')
                <a href="{{ route('users') }}" wire:navigate
                    class="group relative flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ request()->routeIs('users') ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-950' }}"
                    :class="sidebarCollapsed ? 'md:justify-center' : ''">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11a4 4 0 100-8 4 4 0 000 8zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z">
                        </path>
                    </svg>
                    <span class="min-w-0 flex-1 truncate transition-all duration-200"
                        :class="sidebarCollapsed ? 'md:w-0 md:flex-none md:opacity-0' : 'opacity-100'">إدارة المستخدمين</span>
                    <span x-show="sidebarCollapsed" x-cloak
                        class="pointer-events-none absolute right-full top-1/2 z-50 mr-3 hidden -translate-y-1/2 whitespace-nowrap rounded-lg bg-gray-900 px-2.5 py-1.5 text-xs text-white opacity-0 shadow-lg transition group-hover:opacity-100 md:block">
                        إدارة المستخدمين
                    </span>
                </a>
            @endcan
        </nav>
    </div>

    <div class="border-t border-gray-100 bg-white p-3">
        <div class="flex items-center gap-3 rounded-xl p-2 transition hover:bg-gray-50"
            :class="sidebarCollapsed ? 'md:justify-center' : ''">
            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-gray-900 text-sm font-bold text-white shadow-sm">
                {{ auth()->user()->name[0] }}
            </div>
            <div class="min-w-0 overflow-hidden whitespace-nowrap transition-all duration-200"
                :class="sidebarCollapsed ? 'md:w-0 md:opacity-0' : 'opacity-100'">
                <h4 class="truncate text-sm font-bold text-gray-900">{{ auth()->user()->name }}</h4>
                <p class="truncate text-xs text-gray-400">{{ auth()->user()->role }}</p>
            </div>
        </div>
    </div>
</aside>

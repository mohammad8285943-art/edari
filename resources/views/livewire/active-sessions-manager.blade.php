<main class="flex-1 overflow-y-auto p-6 space-y-6 relative">

    {{-- رسائل النجاح (Flash Messages) --}}
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 bg-green-50 rounded-xl border border-green-200 flex items-center justify-between shadow-sm">
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    {{-- قسم الإحصائيات وأزرار التحكم العلوية --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-4">
            {{-- إجمالي المتواجدين حالياً --}}
            <div class="bg-indigo-50/60 border border-indigo-100 px-4 py-2.5 rounded-xl flex items-center gap-3">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span class="text-indigo-600 font-bold text-xl">{{ $onlineUsersCount }}</span>
                <span class="text-gray-600 text-sm font-medium">المستخدمين المتواجدين الآن</span>
            </div>
        </div>

        {{-- حقل البحث السريع --}}
        <div class="relative w-full max-w-md">
            <input type="text" wire:model.live="search" placeholder="ابحث بالاسم، اسم المستخدم أو عنوان IP" class="w-full pr-4 pl-4 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-sm">
        </div>
    </div>

    {{-- جدول عرض المتواجدين حالياً --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs font-bold uppercase tracking-wider">
                        <th class="p-4">المستخدم</th>
                        <th class="p-4">عنوان IP</th>
                        <th class="p-4">الجهاز / المتصفح</th>
                        <th class="p-4">آخر ظهور</th>
                        <th class="p-4 text-center">العمليات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($activeSessions as $session)
                        <tr class="hover:bg-gray-50/40 transition">
                            {{-- بيانات المستخدم --}}
                            <td class="p-4">
                                <div class="font-semibold text-gray-900">{{ $session->name }}</div>
                                <div class="text-xs text-gray-400"><span>{{ $session->username }}</span></div>
                            </td>

                            {{-- عنوان الـ IP --}}
                            <td class="p-4 font-mono text-gray-600">
                                <span class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $session->ip_address }}</span>
                            </td>

                            {{-- معلومات المتصفح والجهاز --}}
                            <td class="p-4 text-xs text-gray-500 max-w-xs truncate" title="{{ $session->user_agent }}">
                                {{ \Illuminate\Support\Str::limit($session->user_agent, 45) }}
                            </td>

                            {{-- وقت آخر نشاط --}}
                            <td class="p-4 text-xs font-medium text-gray-600">
                                {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                            </td>

                            {{-- زر الطرد --}}
                            <td class="p-4 text-center">
                                <button onclick="confirm('هل أنت تأكد من طرد هذا المستخدم وإنهاء جلسته؟') || event.stopImmediatePropagation()"
                                        wire:click="logoutUser({{ $session->user_id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-xl text-xs font-bold transition cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    طرد من الموقع
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400 bg-gray-50/50">لا يوجد مستخدمون متواجدون حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- الترقيم (Pagination Links) --}}
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $activeSessions->links() }}
        </div>
    </div>

</main>

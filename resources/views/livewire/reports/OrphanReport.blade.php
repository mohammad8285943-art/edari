<main class="flex-1 overflow-y-auto p-4 space-y-5 relative text-xs" dir="rtl">
    {{-- كروت الإحصائيات العامة --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <!-- إجمالي الأيتام -->
        <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-gray-500 font-medium">إجمالي الأيتام</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-indigo-600 font-bold text-lg">{{ $orphansData['totals']['total_orphans'] }}</span>
                <span class="text-[10px] text-gray-400">يتيم</span>
            </div>
        </div>

        <!-- المكفولون -->
        <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-gray-500 font-medium">عدد الكفالات</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-emerald-600 font-bold text-lg">{{ $orphansData['totals']['guaranteed_unique_ssn'] }}</span>
                <span class="text-[10px] text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">كفالة</span>
            </div>
        </div>

        <!-- غير المكفولين -->
        <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-gray-500 font-medium">غير المكفولين</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-rose-600 font-bold text-lg">{{ $orphansData['totals']['not_guaranteed_count'] }}</span>
                <span class="text-[10px] text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded">بحاجة</span>
            </div>
        </div>

        <!-- الأيتام المصابون -->
        <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-gray-500 font-medium">الأيتام المصابون</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-amber-600 font-bold text-lg">{{ $orphansData['totals']['injured_count'] }}</span>
                <span class="text-[10px] text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded">رعاية خاصة</span>
            </div>
        </div>

        <!-- إجمالي الأرامل -->
        <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-gray-500 font-medium">إجمالي الأرامل</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-purple-600 font-bold text-lg">{{ $widowsData['totals']['total_widows'] }}</span>
                <span class="text-[10px] text-purple-600 bg-purple-50 px-1.5 py-0.5 rounded">أرملة</span>
            </div>
        </div>

        <!-- مساعدات الفترة -->
        <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-gray-500 font-medium">مساعدات الفترة المحددة</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-blue-600 font-bold text-lg">{{ $aidData['grand_total'] }}</span>
                <span class="text-[10px] text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">مستفيد</span>
            </div>
        </div>
    </div>

    {{-- أولاً: جدول إحصائيات الأيتام --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-3.5 border-b border-gray-50 bg-gray-50/40 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                إحصائيات الأيتام حسب الأقسام
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100">
                        <th class="p-3 w-56">البيان</th>
                        @foreach($orphansData['departments'] as $dept)
                            <th class="p-3 text-center">{{ $dept->name }}</th>
                        @endforeach
                        <th class="p-3 text-center bg-indigo-50/50 text-indigo-900 border-r border-gray-100 font-extrabold">المجموع</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @foreach($orphansData['matrix'] as $row)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-3 font-semibold text-gray-800">{{ $row['label'] }}</td>
                            @foreach($orphansData['departments'] as $dept)
                                <td class="p-3 text-center font-medium">
                                    {{ $row['values'][$dept->id] ?? 0 }}
                                </td>
                            @endforeach
                            <td class="p-3 text-center font-bold bg-indigo-50/30 text-indigo-700 border-r border-gray-100">
                                {{ $row['total'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ثانياً: جدول إحصائيات الأرامل --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-3.5 border-b border-gray-50 bg-gray-50/40 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                إحصائيات الأرامل حسب الأقسام
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100">
                        <th class="p-3 w-56">البيان</th>
                        @foreach($widowsData['departments'] as $dept)
                            <th class="p-3 text-center">{{ $dept->name }}</th>
                        @endforeach
                        <th class="p-3 text-center bg-purple-50/50 text-purple-900 border-r border-gray-100 font-extrabold">المجموع</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @foreach($widowsData['matrix'] as $row)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-3 font-semibold text-gray-800">{{ $row['label'] }}</td>
                            @foreach($widowsData['departments'] as $dept)
                                <td class="p-3 text-center font-medium">
                                    {{ $row['values'][$dept->id] ?? 0 }}
                                </td>
                            @endforeach
                            <td class="p-3 text-center font-bold bg-purple-50/30 text-purple-700 border-r border-gray-100">
                                {{ $row['total'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ثالثاً: تقرير المساعدات --}}
    <div class="space-y-3">
        <!-- فلاتر التاريخ -->
     <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
    <div>
        <label class="block font-medium text-gray-500 mb-1">من تاريخ التنفيذ</label>
        <input type="date" wire:model.live="fromDate"
            class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 bg-white">
    </div>

    <div>
        <label class="block font-medium text-gray-500 mb-1">إلى تاريخ التنفيذ</label>
        <input type="date" wire:model.live="toDate"
            class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 bg-white">
    </div>

    <div class="flex items-end gap-2 sm:col-span-2">
        <button type="button" wire:click="resetDateFilter"
            class="px-3 py-1.5 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition font-medium cursor-pointer">
            الشهر الحالي
        </button>
        <button type="button" wire:click="$set('fromDate', ''); $set('toDate', '')"
            class="px-3 py-1.5 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition font-medium cursor-pointer">
            عرض كل التواريخ
        </button>
    </div>
</div>

        <!-- جدول المساعدات -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-3.5 border-b border-gray-50 bg-gray-50/40 flex items-center justify-between">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    توزيع المساعدات حسب الأسماء والأقسام المستنتجة
                </h3>
                <span class="text-gray-400 text-[11px]">مبني على تاريخ التنفيذ date_execution</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100">
                            <th class="p-3 w-56">البيان</th>
                            @foreach($aidData['columns'] as $colKey => $colLabel)
                                <th class="p-3 text-center">{{ $colLabel }}</th>
                            @endforeach
                            <th class="p-3 text-center bg-emerald-50/50 text-emerald-900 border-r border-gray-100 font-extrabold">المجموع</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse($aidData['matrix'] as $row)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-3 font-semibold text-gray-800">{{ $row['label'] }}</td>
                                @foreach($aidData['columns'] as $colKey => $colLabel)
                                    <td class="p-3 text-center font-medium">
                                        {{ $row['values'][$colKey] ?? 0 }}
                                    </td>
                                @endforeach
                                <td class="p-3 text-center font-bold bg-emerald-50/30 text-emerald-700 border-r border-gray-100">
                                    {{ $row['total'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-gray-400 bg-gray-50/50">
                                    لا توجد بيانات مطابقة للفترة المحددة.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

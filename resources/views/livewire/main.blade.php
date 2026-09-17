<main class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-6 text-xs sm:text-sm" dir="rtl">

    {{-- البطاقات الإحصائية العلوية السريعة --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-gray-500 font-medium">إجمالي الأيتام</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-indigo-600 font-bold text-xl">{{ number_format($orphans->total_orphans ?? 0) }}</span>
                <span class="text-[11px] text-gray-400">يتيم</span>
            </div>
        </div>

        <div class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-gray-500 font-medium">الأيتام المكفولين</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-emerald-600 font-bold text-xl">{{ number_format($orphans->guaranteed_count ?? 0) }}</span>
                <span class="text-[11px] text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">مكفول</span>
            </div>
        </div>

        <div class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-gray-500 font-medium">غير المكفولين</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-rose-600 font-bold text-xl">{{ number_format($orphans->not_guaranteed_count ?? 0) }}</span>
                <span class="text-[11px] text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded">بحاجة</span>
            </div>
        </div>

        <div class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-gray-500 font-medium">إجمالي الكفالات</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-cyan-600 font-bold text-xl">{{ number_format($totalGuarantees ?? 0) }}</span>
                <span class="text-[11px] text-cyan-600 bg-cyan-50 px-1.5 py-0.5 rounded">كفالة</span>
            </div>
        </div>

        <div class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-gray-500 font-medium">إجمالي الأرامل</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-purple-600 font-bold text-xl">{{ number_format($widows->total_widows ?? 0) }}</span>
                <span class="text-[11px] text-purple-600 bg-purple-50 px-1.5 py-0.5 rounded">أرملة</span>
            </div>
        </div>

        <div class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-gray-500 font-medium">إجمالي المساعدات</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-blue-600 font-bold text-xl">{{ number_format($aids->total_aids ?? 0) }}</span>
                <span class="text-[11px] text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">مستفيد</span>
            </div>
        </div>
    </div>

    {{-- الجداول التلخيصية المباشرة بدون تفكيك الأعمدة --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- ملخص الأيتام الإجمالي --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-3.5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                    إحصائيات ملف الأيتام الإجمالية
                </h3>
            </div>
            <table class="w-full text-right border-collapse">
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    <tr class="hover:bg-gray-50/50">
                        <td class="p-3 font-medium text-gray-600">إجمالي الأيتام</td>
                        <td class="p-3 text-left font-bold text-indigo-700">{{ number_format($orphans->total_orphans ?? 0) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50/50">
                        <td class="p-3 font-medium text-gray-600">الأيتام تحت 16 سنة</td>
                        <td class="p-3 text-left font-bold text-gray-800">{{ number_format($orphans->under_16 ?? 0) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50/50">
                        <td class="p-3 font-medium text-gray-600">الأيتام 16 سنة فأكثر</td>
                        <td class="p-3 text-left font-bold text-gray-800">{{ number_format($orphans->equal_over_16 ?? 0) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50/50">
                        <td class="p-3 font-medium text-gray-600">الأيتام المكفولين (رؤوس)</td>
                        <td class="p-3 text-left font-bold text-emerald-600">{{ number_format($orphans->guaranteed_count ?? 0) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50/50">
                        <td class="p-3 font-medium text-gray-600">إجمالي عقود الكفالات المسجلة</td>
                        <td class="p-3 text-left font-bold text-cyan-600">{{ number_format($totalGuarantees ?? 0) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50/50">
                        <td class="p-3 font-medium text-gray-600">الأيتام غير المكفولين</td>
                        <td class="p-3 text-left font-bold text-rose-600">{{ number_format($orphans->not_guaranteed_count ?? 0) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50/50">
                        <td class="p-3 font-medium text-gray-600">الأيتام المصابون (بحاجة رعاية صحية)</td>
                        <td class="p-3 text-left font-bold text-amber-600">{{ number_format($orphans->injured_count ?? 0) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50/50">
                        <td class="p-3 font-medium text-gray-600">الناجي الوحيد</td>
                        <td class="p-3 text-left font-bold text-gray-800">{{ number_format($orphans->lone_survivor_count ?? 0) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50/50">
                        <td class="p-3 font-medium text-gray-600">يتيم الأبوين</td>
                        <td class="p-3 text-left font-bold text-gray-800">{{ number_format($orphans->both_parents_dead_count ?? 0) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- ملخص الأرامل والمساعدات --}}
        <div class="space-y-5">
            {{-- جدول الأرامل --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-3.5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                        إحصائيات الأرامل
                    </h3>
                </div>
                <table class="w-full text-right border-collapse">
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        <tr class="hover:bg-gray-50/50">
                            <td class="p-3 font-medium text-gray-600">إجمالي الأرامل المسجلات</td>
                            <td class="p-3 text-left font-bold text-purple-700">{{ number_format($widows->total_widows ?? 0) }}</td>
                        </tr>
                        <tr class="hover:bg-gray-50/50">
                            <td class="p-3 font-medium text-gray-600">أرامل يعلن أيتاماً</td>
                            <td class="p-3 text-left font-bold text-emerald-600">{{ number_format($widows->with_orphans ?? 0) }}</td>
                        </tr>
                        <tr class="hover:bg-gray-50/50">
                            <td class="p-3 font-medium text-gray-600">أرامل بدون أيتام</td>
                            <td class="p-3 text-left font-bold text-gray-800">{{ number_format($widows->without_orphans ?? 0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- جدول المساعدات --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-3.5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        إجمالي المساعدات المنفذة
                    </h3>
                </div>
                <table class="w-full text-right border-collapse">
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        <tr class="hover:bg-gray-50/50">
                            <td class="p-3 font-medium text-gray-600">مساعدات الأيتام</td>
                            <td class="p-3 text-left font-bold text-indigo-600">{{ number_format($aids->orphan_aids ?? 0) }}</td>
                        </tr>
                        <tr class="hover:bg-gray-50/50">
                            <td class="p-3 font-medium text-gray-600">مساعدات الأرامل</td>
                            <td class="p-3 text-left font-bold text-purple-600">{{ number_format($aids->widow_aids ?? 0) }}</td>
                        </tr>
                        <tr class="hover:bg-gray-50/50 bg-blue-50/30">
                            <td class="p-3 font-bold text-blue-900">إجمالي المساعدات الموزعة</td>
                            <td class="p-3 text-left font-extrabold text-blue-700">{{ number_format($aids->total_aids ?? 0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

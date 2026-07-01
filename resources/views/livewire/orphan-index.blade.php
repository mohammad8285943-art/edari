<main class="flex-1 overflow-y-auto p-4 space-y-4 relative text-xs">

    {{-- قسم الإحصائيات العلوي وزر التصدير --}}
    <div
        class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-white p-3 rounded-xl shadow-sm border border-gray-100">
        <div class="bg-indigo-50 border border-indigo-100 px-3 py-1.5 rounded-lg flex items-center gap-3">
            <span class="text-gray-600 font-medium">إجمالي الأيتام المتاحين:</span>
            <span class="text-indigo-600 font-bold text-base">{{ $totalCount }}</span>
        </div>
        @can('orphan.export')
        <button wire:click="exportToExcel"
            class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-1.5 rounded-lg font-medium transition flex items-center justify-center gap-1.5 cursor-pointer shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            تصدير النتائج (Excel)
        </button>
        @endcan
    </div>

    {{-- لوحة الفلاتر المصغرة والمحسنة --}}
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm space-y-3">
        <div class="flex items-center justify-between border-b border-gray-50 pb-2">
            <h4 class="font-bold text-gray-700 flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-indigo-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                تصفية البيانات المتقدمة
            </h4>
        </div>

        {{-- شبكة الفلاتر المكثفة - 6 أعمدة على الشاشات الكبيرة لتوفير المساحة --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2.5">
            {{-- فلاتر اليتيم --}}
            <div>
                <label class="block font-medium text-gray-500 mb-1">اسم اليتيم</label>
                <input type="text" wire:model="searchName" placeholder="الاسم المباشر..."
                    class="w-full px-2.5 py-1.5  border border-black rounded-lg outline-none focus:border-indigo-500 text-x">
            </div>

            <div>
                <label class="block font-medium text-gray-500 mb-1">هوية اليتيم</label>
                <input type="text" wire:model="searchSsn" placeholder="9 خانات..."
                    class="w-full px-2.5 py-1.5  border border-black rounded-lg outline-none focus:border-indigo-500 text-xs">
            </div>

            {{-- فلاتر الأب --}}
            <div>
                <label class="block font-medium text-gray-500 mb-1">اسم الأب</label>
                <input type="text" wire:model="searchFName" placeholder="اسم الأب..."
                    class="w-full px-2.5 py-1.5  border border-black rounded-lg outline-none focus:border-indigo-500 text-xs">
            </div>

            <div>
                <label class="block font-medium text-gray-500 mb-1">هوية الأب</label>
                <input type="text" wire:model="searchFSsn" placeholder="هوية الأب..."
                    class="w-full px-2.5 py-1.5  border border-black rounded-lg outline-none focus:border-indigo-500 text-xs">
            </div>

            {{-- فلاتر الأم والوكيل --}}
            <div>
                <label class="block font-medium text-gray-500 mb-1">اسم الأم</label>
                <input type="text" wire:model="searchMName" placeholder="اسم الأم..."
                    class="w-full px-2.5 py-1.5  border border-black rounded-lg outline-none focus:border-indigo-500 text-xs">
            </div>

            <div>
                <label class="block font-medium text-gray-500 mb-1">هوية الأم</label>
                <input type="text" wire:model="searchMSsn" placeholder="هوية الأم..."
                    class="w-full px-2.5 py-1.5  border border-black rounded-lg outline-none focus:border-indigo-500 text-xs">
            </div>

            <div>
                <label class="block font-medium text-gray-500 mb-1">اسم الوكيل</label>
                <input type="text" wire:model="searchAName" placeholder="اسم الوكيل الكامل..."
                    class="w-full px-2.5 py-1.5  border border-black rounded-lg outline-none focus:border-indigo-500 text-xs">
            </div>

            {{-- فلاتر العمر المعتمدة على تاريخ الميلاد --}}
            <div>
                <label class="block font-medium text-gray-500 mb-1">العمر من (سنوات)</label>
                <input type="number" wire:model="ageMin" placeholder="أكبر من أو يساوي..."
                    class="w-full px-2.5 py-1.5  border border-black rounded-lg outline-none focus:border-indigo-500 text-xs">
            </div>

            <div>
                <label class="block font-medium text-gray-500 mb-1">العمر إلى (سنوات)</label>
                <input type="number" wire:model="ageMax" placeholder="أصغر من أو يساوي..."
                    class="w-full px-2.5 py-1.5  border border-black rounded-lg outline-none focus:border-indigo-500 text-xs">
            </div>

            {{-- فلاتر الخيارات الثابتة --}}
            <div>
                <label class="block font-medium text-gray-500 mb-1">الجنس</label>
                <select wire:model="filterSex"
                    class="w-full px-2 py-1.5  border border-black rounded-lg outline-none focus:border-indigo-500 text-xs bg-white">
                    <option value="">ذكر/أنثى</option>
                    <option value="ذكر">ذكر</option>
                    <option value="أنثى">أنثى</option>
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-500 mb-1">الحالة الصحية</label>
                <select wire:model="filterHealth"
                    class="w-full px-2 py-1.5  border border-black rounded-lg outline-none focus:border-indigo-500 text-xs bg-white">
                    <option value="">الكل</option>
                    <option value="good">جيدة</option>
                    <option value="not_good">يعاني من مرض (ليست جيدة)</option>
                </select>
            </div>
            @if (auth()->user()->view == 1)
                <div>
                    <label class="block font-medium text-gray-500 mb-1">الشعبة (تلغى عند تحديد المسجد)</label>
                    <select wire:model="filterDepartment"
                        class="w-full px-2 py-1.5  border border-black rounded-lg outline-none focus:border-indigo-500 text-xs bg-white">
                        <option value="">كل الشعب</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach

                    </select>
                </div>
            @endif
            @if (auth()->user()->view != 3)
            <div>
                <label class="block font-medium text-gray-500 mb-1">المسجد</label>
                <select wire:model="filterMosque"
                    class="w-full px-2 py-1.5  border border-black rounded-lg outline-none focus:border-indigo-500 text-xs bg-white">
                    <option value="">كل المساجد</option>
                    @foreach ($mosques as $mosque)
                        <option value="{{ $mosque->id }}">{{ $mosque->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
            @endif

        {{-- أزرار التحكم بالفلترة والتصفية --}}
        <div class="flex justify-end gap-1.5 pt-2 border-t border-gray-50">
            <button type="button" wire:click="resetFilter"
                class="px-3 py-1.5 text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-lg transition cursor-pointer">
                إعادة تعيين
            </button>
            <button type="button" wire:click="applyFilter"
                class="px-4 py-1.5 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition flex items-center gap-1 cursor-pointer font-medium shadow-sm">
                تصفية النتائج
            </button>
        </div>
    </div>

    {{-- جدول عرض الأيتام المحدث --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden text-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                        <th class="p-3">اليتيم والهوية</th>
                        <th class="p-3">تفاصيل الميلاد والصحة</th>
                        <th class="p-3">الأب</th>
                        <th class="p-3">الأم</th>
                        <th class="p-3">الوكيل والنطاق</th>
                        <th class="p-3">ارقام التواصل</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($orphans as $orphan)
                        <tr class="hover:bg-gray-50/30 transition">
                            <td class="p-3">
                                <div class="font-bold text-gray-900">{{ $orphan->name }}</div>
                                <div class="text-gray-400 mt-0.5">{{ $orphan->SSN }}</div>
                            </td>
                            <td class="p-3 space-y-0.5">
                                <div><span class="text-gray-400">الميلاد:</span>
                                    {{ $orphan->barth ? $orphan->barth->format('Y-m-d') : '-' }}</div>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span
                                        class="px-1.5 py-0.2 bg-gray-100 rounded text-[10px] font-medium">{{ $orphan->sex }}</span>
                                    <span
                                        class="px-1.5 py-0.2 rounded text-[10px] font-medium {{ $orphan->health == 'جيدة' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                        الصحة: {{ $orphan->health }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-3">
                                <div class="font-medium text-gray-800">{{ $orphan->f_name ?? '-' }}</div>
                                <div class="text-gray-400 text-[11px]">{{ $orphan->f_ssn }}</div>
                            </td>
                            <td class="p-3">
                                <div class="font-medium text-gray-800">{{ $orphan->m_name ?? '-' }}</div>
                                <div class="text-gray-400 text-[11px]">{{ $orphan->m_ssn }}
                                    <span
                                        class="px-1.5 py-0.5 rounded text-[10px] font-medium {{ $orphan->m_d == 'حي' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                        {{ $orphan->m_d }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-3">
                                <div class="font-medium text-gray-800"><span
                                        class="text-gray-400 font-normal">الوكيل:</span> {{ $orphan->a_name ?? '-' }}
                                </div>
                                <div class="text-[11px] text-gray-400 mt-0.5">
                                    {{ $orphan->department?->name ?? 'غير محدد' }}:
                                    {{ $orphan->mosque?->name ?? 'غير محدد' }}</div>
                            </td>
                            <td class="p-3">
                                <div class="text-[11px] text-gray-400 mt-0.5"> {{ $orphan->mobile ?? 'لا يوجد' }}
                                </div>
                                <div class="text-[11px] text-gray-400 mt-0.5"> {{ $orphan->mobile2 ?? 'لا يوجد' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400 bg-gray-50/50">لا توجد نتائج تطابق
                                التصفية المطلوبة.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 border-t border-gray-100 bg-gray-50/50">
            {{ $orphans->links() }}
        </div>
    </div>

</main>

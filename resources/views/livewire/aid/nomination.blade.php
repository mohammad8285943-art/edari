<main class="flex-1 overflow-y-auto p-4 space-y-4 relative text-xs">
    {{-- رأس الصفحة --}}
    <div
        class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-white p-3 rounded-xl shadow-sm border border-gray-100">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('orphans.aids.show', $aid->id) }}"
                    class="text-indigo-600 hover:text-indigo-800 flex items-center gap-1 font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    العودة لبيانات المساعدة
                </a>
                <span class="text-gray-300">|</span>
                <span class="font-bold text-gray-800">ترشيح: {{ $aid->name }}</span>
                <span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded text-[11px] font-bold">
                    {{ $aid->beneficiary_type === 'orphan' ? 'فئة الأيتام' : 'فئة الأرامل' }}
                </span>
            </div>
        </div>

        <div>
            <button type="button" wire:click="addSelectedToAid"
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-1.5 rounded-lg font-bold transition flex items-center gap-1.5 cursor-pointer shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                إضافة المحددين للمساعدة ({{ count($selected) }})
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-3 bg-green-100 text-green-800 border border-green-200 rounded-lg font-medium">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-3 bg-red-100 text-red-800 border border-red-200 rounded-lg font-medium">
            {{ session('error') }}
        </div>
    @endif

    {{-- لوحة الفلاتر وفترة الاستفادة وقواعد منع التكرار --}}
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm space-y-3">
        <div class="border-b border-gray-100 pb-2 flex items-center justify-between">
            <h4 class="font-bold text-gray-700 flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
                إعدادات الترشيح وفحص الاستفادة السابقة
            </h4>
        </div>

        {{-- إعدادات التحقق من الاستفادة والأسرة --}}
        <div
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 bg-indigo-50/50 p-3 rounded-xl border border-indigo-100">
            <div>
                <label class="block font-bold text-gray-700 mb-1">فترة فحص الاستفادة (من)</label>
                <input type="date" wire:model.live="dateFrom"
                    class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none bg-white text-xs">
            </div>

            <div>
                <label class="block font-bold text-gray-700 mb-1">فترة فحص الاستفادة (إلى)</label>
                <input type="date" wire:model.live="dateTo"
                    class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none bg-white text-xs">
            </div>

            <div>
                <label class="block font-bold text-gray-700 mb-1">طريقة التحقق</label>
                <select wire:model.live="checkMode"
                    class="w-full px-2 py-1.5 border border-black rounded-lg outline-none bg-white text-xs">
                    <option value="family">التحقق على مستوى الأسرة (الأم والإخوة)</option>
                    <option value="individual">التحقق على مستوى الفرد فقط</option>
                </select>
            </div>

            <div class="flex items-center pt-5">
                <label class="flex items-center gap-2 cursor-pointer font-bold text-gray-700">
                    <input type="checkbox" wire:model.live="excludeBeneficiaries"
                        class="h-4 w-4 text-indigo-600 rounded">
                    <span>استبعاد المستفيدين خلال الفترة</span>
                </label>
            </div>
        </div>

        {{-- فلاتر البيانات الحالية --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-2.5 pt-2">
            <div>
                <label class="block font-medium text-gray-500 mb-1">بحث شامل (الاسم / الهوية)</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="ابحث هنا..."
                    class="w-full px-2.5 py-1.5 border border-black rounded-lg outline-none focus:border-indigo-500 text-xs">
            </div>

            <div>
                <label class="block font-medium text-gray-500 mb-1">الشعبة / القسم</label>
                <select wire:model.live="filterDepartment"
                    class="w-full px-2 py-1.5 border border-black rounded-lg outline-none bg-white text-xs">
                    <option value="">كل الشعب</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-500 mb-1">المسجد</label>
                <select wire:model.live="filterMosque"
                    class="w-full px-2 py-1.5 border border-black rounded-lg outline-none bg-white text-xs">
                    <option value="">كل المساجد</option>
                    @foreach ($mosques as $mosque)
                        <option value="{{ $mosque->id }}">{{ $mosque->name }}</option>
                    @endforeach
                </select>
            </div>

            @if ($aid->beneficiary_type === 'orphan')
                <div class="grid grid-cols-2 gap-1.5">
                    <div>
                        <label class="block font-medium text-gray-500 mb-1">العمر الأدنى</label>
                        <input type="number" wire:model.live="filterMinAge" placeholder="من"
                            class="w-full px-2 py-1.5 border border-black rounded-lg outline-none text-xs">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-500 mb-1">العمر الأعلى</label>
                        <input type="number" wire:model.live="filterMaxAge" placeholder="إلى"
                            class="w-full px-2 py-1.5 border border-black rounded-lg outline-none text-xs">
                    </div>
                </div>
            @else
                <div class="grid grid-cols-2 gap-1.5">
                    <div>
                        <label class="block font-medium text-gray-500 mb-1">الأيتام (أدنى)</label>
                        <input type="number" wire:model.live="filterMinOrphans" placeholder="من"
                            class="w-full px-2 py-1.5 border border-black rounded-lg outline-none text-xs">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-500 mb-1">الأيتام (أعلى)</label>
                        <input type="number" wire:model.live="filterMaxOrphans" placeholder="إلى"
                            class="w-full px-2 py-1.5 border border-black rounded-lg outline-none text-xs">
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- جدول المرشحين المؤهلين --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden text-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider">
                        <th class="p-3 w-10">
                            <input type="checkbox" wire:model.live="selectAll"
                                class="h-4 w-4 text-indigo-600 rounded">
                        </th>
                        <th class="p-3">الاسم</th>
                        <th class="p-3">رقم الهوية</th>
                        @if ($aid->beneficiary_type === 'orphan')
                            <th class="p-3">العمر</th>
                            <th class="p-3">اسم الأم / هوية الأم</th>
                        @else
                            <th class="p-3">اسم الزوج</th>
                            <th class="p-3">عدد الأيتام</th>
                        @endif
                        <th class="p-3">القسم / المسجد</th>
                        <th class="p-3">حالة الأهلية</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($nominees as $nominee)
                        <tr class="hover:bg-gray-50/30 transition">
                            <td class="p-3">
                                <input type="checkbox" wire:model.live="selected" value="{{ $nominee->id }}"
                                    class="h-4 w-4 text-indigo-600 rounded">
                            </td>
                            <td class="p-3 font-bold text-gray-900">{{ $nominee->name }}</td>
                            <td class="p-3 font-medium">
                                {{ $aid->beneficiary_type === 'orphan' ? $nominee->SSN : $nominee->ssn }}</td>
                            @if ($aid->beneficiary_type === 'orphan')
                                <td class="p-3">
                                    {{ $nominee->barth ? \Carbon\Carbon::parse($nominee->barth)->age . ' سنة' : 'غير محدد' }}
                                </td>
                                <td class="p-3">
                                    <div>{{ $nominee->m_name ?? '-' }}</div>
                                    <div class="text-[11px] text-gray-400 mt-0.5">{{ $nominee->m_ssn ?? '-' }}</div>
                                </td>
                            @else
                                <td class="p-3">{{ $nominee->husband ?? '-' }}</td>
                                <td class="p-3 font-bold text-indigo-700">{{ $nominee->orphan_count }} أيتام</td>
                            @endif
                            <td class="p-3">
                                <div>{{ $nominee->department?->name ?? 'غير محدد' }}</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">
                                    {{ $nominee->mosque?->name ?? 'غير محدد' }}</div>
                            </td>
                            <td class="p-3">
                                <span
                                    class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full text-[11px] font-bold">
                                    مؤهل للترشيح
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-400 bg-gray-50/50">
                                لا يوجد مرشحون مؤهلون وفقًا لشروط الفلترة وفترة الاستفادة المحددة.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div
            class="p-3 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>
                {{ $nominees->links() }}
            </div>
            @if (count($selected) > 0)
                <button type="button" wire:click="addSelectedToAid"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-1.5 rounded-lg font-bold transition flex items-center gap-1.5 cursor-pointer shadow-sm text-xs">
                    إضافة المحددين ({{ count($selected) }})
                </button>
            @endif
        </div>
    </div>
</main>

<main class="flex-1 overflow-y-auto p-4 space-y-4 relative text-xs print:p-0 print:overflow-visible">

    {{-- شريط العنوان والوصف وزر الطباعة (شاشة العرض) --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-white p-4 rounded-xl shadow-sm border border-gray-100 no-print">
        <div>
            <h1 class="text-base font-bold text-gray-800 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                البحث الشامل عن مستفيد
            </h1>
            <p class="text-gray-400 mt-0.5 text-xs">ابحث برقم الهوية لعرض كافة البيانات والروابط من مختلف سجلات النظام.</p>
        </div>

        @if($hasSearched && ($orphansCount + $widowsCount + $guaranteesCount + $aidsCount) > 0)
            <button type="button" onclick="window.print()"
                class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium transition flex items-center justify-center gap-1.5 cursor-pointer shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                طباعة التقرير
            </button>
        @endif
    </div>

    {{-- صندوق البحث الرئيسي --}}
    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm space-y-3 no-print">
        <form wire:submit.prevent="search" class="space-y-3">
            <div class="flex flex-col sm:flex-row items-end gap-3 max-w-xl">
                <div class="flex-1 w-full">
                    <label class="block font-medium text-gray-600 mb-1">رقم الهوية / SSN</label>
                    <div class="relative">
                        <input type="text" wire:model.defer="ssn" placeholder="أدخل رقم الهوية كاملاً..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs @error('ssn') border-red-500 @enderror">
                        <div wire:loading wire:target="search" class="absolute left-2.5 top-2.5">
                            <svg class="animate-spin h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </div>
                    </div>
                    @error('ssn')
                        <span class="text-[11px] text-red-600 mt-1 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="submit" wire:loading.attr="disabled"
                        class="flex-1 sm:flex-initial px-5 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition flex items-center justify-center gap-1.5 cursor-pointer font-medium shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>بحث</span>
                    </button>

                    @if($hasSearched)
                        <button type="button" wire:click="resetSearch"
                            class="px-3 py-2 text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-lg transition cursor-pointer">
                            إلغاء
                        </button>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- محتوى النتائج --}}
    @if($hasSearched)
        @php
            $totalResults = $orphansCount + $widowsCount + $guaranteesCount + $aidsCount;
        @endphp

        @if($totalResults === 0)
            {{-- حالة عدم وجود أي نتائج --}}
            <div class="bg-white p-8 rounded-xl border border-gray-100 shadow-sm text-center">
                <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mx-auto mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-800">لم يتم العثور على أي بيانات</h3>
                <p class="text-gray-400 mt-1">لا توجد سجلات مرتبطة برقم الهوية ({{ $searchedSsn }}) في جميع جداول النظام.</p>
            </div>
        @else
            {{-- شريط الإحصائيات السريعة --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 no-print">
                <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-gray-500 font-medium block">الأيتام</span>
                        <span class="text-indigo-600 font-bold text-base">{{ $orphansCount }}</span>
                    </div>
                    <span class="w-2 h-8 rounded bg-indigo-500"></span>
                </div>

                <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-gray-500 font-medium block">الأرامل</span>
                        <span class="text-rose-600 font-bold text-base">{{ $widowsCount }}</span>
                    </div>
                    <span class="w-2 h-8 rounded bg-rose-500"></span>
                </div>

                <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-gray-500 font-medium block">الكفالات</span>
                        <span class="text-emerald-600 font-bold text-base">{{ $guaranteesCount }}</span>
                    </div>
                    <span class="w-2 h-8 rounded bg-emerald-500"></span>
                </div>

                <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-gray-500 font-medium block">المساعدات</span>
                        <span class="text-amber-600 font-bold text-base">{{ $aidsCount }}</span>
                    </div>
                    <span class="w-2 h-8 rounded bg-amber-500"></span>
                </div>
            </div>

            <div class="print-area space-y-4">
                {{-- 1. قسم بيانات الأيتام --}}
                @if($orphansCount > 0)
                    <div class="space-y-3 print-break-inside-avoid">
                        <div class="flex items-center gap-2 border-r-4 border-indigo-600 pr-2">
                            <h2 class="text-sm font-bold text-gray-800">سجلات الأيتام ({{ $orphansCount }})</h2>
                        </div>

                        @foreach($orphans as $orphan)
                            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden p-4 space-y-3 print:border-gray-300 print:shadow-none">
                                {{-- شريط سبب الظهور والحالة --}}
                                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 pb-2.5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-gray-900 text-sm">{{ $orphan->name }}</span>
                                        <span class="text-gray-400 font-mono text-xs">({{ $orphan->SSN }})</span>
                                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-indigo-50 text-indigo-700">
                                            {{ $orphan->حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين ?? 'يتيم' }}
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap gap-1">
                                        <span class="text-gray-500 text-[11px] self-center">سبب المطابقة:</span>
                                        @foreach($orphan->match_reasons as $reason)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                {{ $reason }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- شبكة تفاصيل بيانات اليتيم --}}
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 text-[11px]">
                                    <div class="bg-gray-50/70 p-2 rounded-lg print:border print:border-gray-200">
                                        <span class="text-gray-400 block mb-0.5">العمر / الجنس:</span>
                                        <span class="font-bold text-gray-800">{{ $orphan->barth->format('d/m/Y') ?? '-' }} - ({{ $orphan->sex ?? '-' }})</span>
                                    </div>

                                    <div class="bg-gray-50/70 p-2 rounded-lg print:border print:border-gray-200">
                                        <span class="text-gray-400 block mb-0.5">الحالة الصحية:</span>
                                        <span class="font-medium {{ $orphan->health == 'جيدة' ? 'text-green-700' : 'text-red-700' }}">
                                            {{ $orphan->health ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="bg-gray-50/70 p-2 rounded-lg print:border print:border-gray-200">
                                        <span class="text-gray-400 block mb-0.5">المرحلة التعليمية:</span>
                                        <span class="font-medium text-gray-800">{{ $orphan->school_level ?? '-' }}</span>
                                    </div>

                                    <div class="bg-gray-50/70 p-2 rounded-lg print:border print:border-gray-200">
                                        <span class="text-gray-400 block mb-0.5">الضرر:</span>
                                        <span class="font-medium text-gray-800">{{ $orphan->damage ?? 'لا يوجد' }}</span>
                                    </div>

                                    <div class="bg-gray-50/70 p-2 rounded-lg print:border print:border-gray-200">
                                        <span class="text-gray-400 block mb-0.5">عدد أفراد الأسرة:</span>
                                        <span class="font-bold text-gray-800">{{ $orphan->count_family ?? '-' }}</span>
                                    </div>

                                    <div class="bg-gray-50/70 p-2 rounded-lg print:border print:border-gray-200">
                                        <span class="text-gray-400 block mb-0.5">أرقام التواصل:</span>
                                        <span class="font-mono text-gray-800">{{ $orphan->mobile ?? '-' }}</span>
                                        @if($orphan->mobile2) <span class="text-gray-400 font-mono">/ {{ $orphan->mobile2 }}</span> @endif
                                    </div>

                                    {{-- بيانات الأب والأم --}}
                                    <div class="bg-gray-50/70 p-2 rounded-lg col-span-2 print:border print:border-gray-200">
                                        <span class="text-gray-400 block mb-0.5">بيانات الأب:</span>
                                        <span class="font-bold text-gray-800">{{ $orphan->f_name ?? '-' }}</span>
                                        <span class="text-gray-500 font-mono text-[10px]"> (هوية: {{ $orphan->f_ssn ?? '-' }})</span>
                                    </div>

                                    <div class="bg-gray-50/70 p-2 rounded-lg col-span-2 print:border print:border-gray-200">
                                        <span class="text-gray-400 block mb-0.5">بيانات الأم:</span>
                                        <span class="font-bold text-gray-800">{{ $orphan->m_name ?? '-' }}</span>
                                        <span class="text-gray-500 font-mono text-[10px]"> (هوية: {{ $orphan->m_ssn ?? '-' }})</span>
                                        @if($orphan->m_d)
                                            <span class="px-1 py-0.2 rounded text-[9px] {{ $orphan->m_d == 'حي' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $orphan->m_d }}</span>
                                        @endif
                                    </div>

                                    {{-- النطاق الجغرافي والمسجد --}}
                                    <div class="bg-indigo-50/40 p-2 rounded-lg col-span-2 border border-indigo-100/50 print:border-gray-200">
                                        <span class="text-indigo-600 font-medium block mb-0.5">القسم والمسجد:</span>
                                        <div class="text-gray-800 font-semibold">
                                            القسم: <span class="text-indigo-900">{{ $orphan->department?->name ?? 'غير محدد' }}</span> |
                                            المسجد: <span class="text-indigo-900">{{ $orphan->mosque?->name ?? 'غير محدد' }}</span>
                                        </div>
                                    </div>

                                    {{-- تفاصيل السكن --}}
                                    <div class="bg-gray-50/70 p-2 rounded-lg col-span-full grid grid-cols-1 sm:grid-cols-3 gap-2 print:border print:border-gray-200">
                                        <div>
                                            <span class="text-gray-400 block mb-0.5">المحافظة والحي:</span>
                                            <span class="text-gray-800 font-medium">{{ $orphan->المحافظة ?? '-' }} - {{ $orphan->المدينة_الحي ?? '-' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 block mb-0.5">السكن الأصلي:</span>
                                            <span class="text-gray-800 font-medium">{{ $orphan->عنوان_المسكن_الأصلي ?? '-' }} ({{ $orphan->حالة_المسكن_الأصلي ?? '-' }})</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-400 block mb-0.5">الإقامة الحالية:</span>
                                            <span class="text-gray-800 font-medium">{{ $orphan->عنوان_الإقامة_الحالية ?? '-' }} ({{ $orphan->طبيعة_الإقامة_الحالية ?? '-' }})</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- 2. قسم بيانات الأرامل --}}
                @if($widowsCount > 0)
                    <div class="space-y-3 print-break-inside-avoid">
                        <div class="flex items-center gap-2 border-r-4 border-rose-600 pr-2">
                            <h2 class="text-sm font-bold text-gray-800">بيانات الأرامل ({{ $widowsCount }})</h2>
                        </div>

                        @foreach($widows as $widow)
                            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden p-4 space-y-3 print:border-gray-300 print:shadow-none">
                                <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                                    <div>
                                        <span class="font-bold text-gray-900 text-sm">{{ $widow->name }}</span>
                                        <span class="text-gray-400 font-mono text-xs">({{ $widow->ssn }})</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-rose-50 text-rose-700">أرملة</span>
                                </div>

                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 text-[11px]">
                                    <div class="bg-gray-50/70 p-2 rounded-lg print:border print:border-gray-200">
                                        <span class="text-gray-400 block mb-0.5">الجوال / جوال غزة:</span>
                                        <span class="font-mono text-gray-800">{{ $widow->mobile ?? '-' }}</span>
                                        @if($widow->gaz_mobile) <span class="font-mono text-gray-500">/ {{ $widow->gaz_mobile }}</span> @endif
                                    </div>

                                    <div class="bg-gray-50/70 p-2 rounded-lg print:border print:border-gray-200">
                                        <span class="text-gray-400 block mb-0.5">الوظيفة:</span>
                                        <span class="font-medium text-gray-800">{{ $widow->job ?? '-' }}</span>
                                    </div>

                                    <div class="bg-gray-50/70 p-2 rounded-lg print:border print:border-gray-200">
                                        <span class="text-gray-400 block mb-0.5">عدد الأيتام:</span>
                                        <span class="font-bold text-gray-800">{{ $widow->orphan_count ?? '0' }}</span>
                                    </div>

                                    <div class="bg-indigo-50/40 p-2 rounded-lg border border-indigo-100/50 print:border-gray-200">
                                        <span class="text-indigo-600 font-medium block mb-0.5">القسم والمسجد:</span>
                                        <span class="font-bold text-gray-800">
                                            {{ $widow->department?->name ?? 'غير محدد' }} | {{ $widow->mosque?->name ?? 'غير محدد' }}
                                        </span>
                                    </div>

                                    <div class="bg-gray-50/70 p-2 rounded-lg col-span-2 print:border print:border-gray-200">
                                        <span class="text-gray-400 block mb-0.5">بيانات الزوج المتوفى:</span>
                                        <span class="font-bold text-gray-800">{{ $widow->husband ?? '-' }}</span>
                                        <span class="text-gray-500 font-mono text-[10px]"> (هوية: {{ $widow->h_ssn ?? '-' }})</span>
                                        <span class="text-gray-400 block text-[10px] mt-0.5">تاريخ الوفاة: {{ $widow->date_death ?? '-' }} | عمله: {{ $widow->h_job ?? '-' }}</span>
                                    </div>

                                    <div class="bg-gray-50/70 p-2 rounded-lg col-span-2 print:border print:border-gray-200">
                                        <span class="text-gray-400 block mb-0.5">العنوان:</span>
                                        <span class="font-medium text-gray-800">{{ $widow->address ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- 3. قسم الكفالات --}}
                @if($guaranteesCount > 0)
                    <div class="space-y-2 print-break-inside-avoid">
                        <div class="flex items-center gap-2 border-r-4 border-emerald-600 pr-2">
                            <h2 class="text-sm font-bold text-gray-800">الكفالات المسجلة ({{ $guaranteesCount }})</h2>
                        </div>

                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden text-xs print:border-gray-300 print:shadow-none">
                            <div class="overflow-x-auto">
                                <table class="w-full text-right border-collapse">
                                    <thead>
                                        <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider print:bg-gray-100 print:text-gray-800">
                                            <th class="p-2.5">المستفيد والهوية</th>
                                            <th class="p-2.5">الكفيل</th>
                                            <th class="p-2.5">فترة الكفالة</th>
                                            <th class="p-2.5">المبلغ</th>
                                            <th class="p-2.5">الحالة</th>
                                            <th class="p-2.5">القسم والمسجد</th>
                                            <th class="p-2.5">المنزل / الجوال</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 text-gray-700">
                                        @foreach($guarantees as $guarantee)
                                            <tr class="hover:bg-gray-50/30 transition">
                                                <td class="p-2.5">
                                                    <div class="font-bold text-gray-900">{{ $guarantee->name }}</div>
                                                    <div class="text-gray-400 font-mono text-[11px]">{{ $guarantee->ssn }}</div>
                                                </td>
                                                <td class="p-2.5 font-medium text-gray-800">{{ $guarantee->guarantor ?? '-' }}</td>
                                                <td class="p-2.5 text-[11px] whitespace-nowrap">
                                                    <div>من: {{ $guarantee->guarantor_start ?? '-' }}</div>
                                                    <div>إلى: {{ $guarantee->guarantor_end ?? '-' }}</div>
                                                </td>
                                                <td class="p-2.5 font-bold text-emerald-600 whitespace-nowrap">
                                                    {{ number_format((float)$guarantee->amount, 2) }}
                                                </td>
                                                <td class="p-2.5">
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-700">
                                                        {{ $guarantee->status ?? 'غير محدد' }}
                                                    </span>
                                                </td>
                                                <td class="p-2.5 text-[11px]">
                                                    <div>{{ $guarantee->department?->name ?? 'غير محدد' }}</div>
                                                    <div class="text-gray-400">{{ $guarantee->mosque?->name ?? 'غير محدد' }}</div>
                                                </td>
                                                <td class="p-2.5 text-[11px]">
                                                    <div class="font-mono">{{ $guarantee->mobile ?? '-' }}</div>
                                                    <div class="text-gray-400 truncate max-w-[150px]">{{ $guarantee->home ?? '-' }}</div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- 4. قسم الاستفادات والمساعدات --}}
                @if($aidsCount > 0)
                    <div class="space-y-2 print-break-inside-avoid">
                        <div class="flex items-center gap-2 border-r-4 border-amber-500 pr-2">
                            <h2 class="text-sm font-bold text-gray-800">الاستفادات والمساعدات ({{ $aidsCount }})</h2>
                        </div>

                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden text-xs print:border-gray-300 print:shadow-none">
                            <div class="overflow-x-auto">
                                <table class="w-full text-right border-collapse">
                                    <thead>
                                        <tr class="bg-gray-50 border-b border-gray-100 text-gray-500 font-bold uppercase tracking-wider print:bg-gray-100 print:text-gray-800">
                                            <th class="p-2.5">المستفيد</th>
                                            <th class="p-2.5">اسم ونوع المساعدة</th>
                                            <th class="p-2.5">القيمة / المتبرع</th>
                                            <th class="p-2.5">التواريخ (ترشيح / تنفيذ)</th>
                                            <th class="p-2.5">الحالة والتنفيذ</th>
                                            <th class="p-2.5">القسم والمسجد</th>
                                            <th class="p-2.5">المحفظة / الجوال</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 text-gray-700">
                                        @foreach($aidBeneficiaries as $ben)
                                            <tr class="hover:bg-gray-50/30 transition">
                                                <td class="p-2.5">
                                                    <div class="font-bold text-gray-900">{{ $ben->name }}</div>
                                                    <div class="text-gray-400 font-mono text-[11px]">{{ $ben->ssn }}</div>
                                                    <span class="text-[10px] text-gray-500">({{ $ben->beneficiary_type ?? 'مستفيد' }})</span>
                                                </td>
                                                <td class="p-2.5">
                                                    <div class="font-medium text-gray-800">{{ $ben->aid?->name ?? '-' }}</div>
                                                    <div class="text-indigo-600 text-[11px]">{{ $ben->aid?->type ?? '-' }}</div>
                                                </td>
                                                <td class="p-2.5 whitespace-nowrap">
                                                    <div class="font-bold text-gray-900">
                                                        {{ $ben->aid?->amount ? number_format($ben->aid->amount, 2) : '-' }}
                                                    </div>
                                                    <div class="text-gray-400 text-[11px]">{{ $ben->aid?->donor ?? '-' }}</div>
                                                </td>
                                                <td class="p-2.5 text-[11px] whitespace-nowrap">
                                                    <div>ترشيح: {{ $ben->aid?->date_nomination?->format('Y-m-d') ?? '-' }}</div>
                                                    <div>تنفيذ: {{ $ben->aid?->date_execution?->format('Y-m-d') ?? '-' }}</div>
                                                </td>
                                                <td class="p-2.5">
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-700 block w-fit mb-1">
                                                        {{ $ben->aid?->status ?? 'غير محدد' }}
                                                    </span>
                                                    <span class="text-[10px] text-gray-400 block">{{ $ben->aid?->execution_place ?? '-' }}</span>
                                                </td>
                                                {{-- عرض النصوص المخزنة كما هي دون فرض علاقات --}}
                                                <td class="p-2.5 text-[11px]">
                                                    <div>{{ $ben->department ?? '-' }}</div>
                                                    <div class="text-gray-400">{{ $ben->mosque ?? '-' }}</div>
                                                </td>
                                                <td class="p-2.5 text-[11px] font-mono">
                                                    <div>{{ $ben->mobile ?? '-' }}</div>
                                                    <div class="text-gray-400">{{ $ben->wallet_number ?? '-' }}</div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    @endif

</main>

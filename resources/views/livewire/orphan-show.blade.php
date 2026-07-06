<main class="flex-1 overflow-y-auto p-6 space-y-6 relative text-xs bg-gray-50/50" dir="rtl">

    {{-- الترويسة العلوية --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-gray-800">{{ $orphan->name }}</h2>
                <p class="text-gray-400 text-[11px] mt-0.5">رقم الهوية: <span class="font-mono text-gray-600">{{ $orphan->SSN }}</span></p>
            </div>
        </div>
        
        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
            <a href="{{ route('orphans.edit', $orphan->id) }}" wire:navigate
                class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition flex items-center gap-1.5 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                تعديل الملف
            </a>
            <a href="{{ route('orphan.index') }}" wire:navigate class="px-4 py-1.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-lg font-medium transition">
                عودة للقائمة
            </a>
        </div>
    </div>

    {{-- شبكة البيانات المقسمة --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- العامود الأيمن: البيانات الأساسية لليتيم وحالته --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- بطاقة اليتيم --}}
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-indigo-600 border-b border-indigo-50 pb-2 flex items-center gap-2">
                    <span class="w-1.5 h-3.5 bg-indigo-600 rounded-sm"></span> البيانات الشخصية لليتيم
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-[11px]">
                    <div>
                        <span class="block text-gray-400 mb-0.5">الجنس</span>
                        <span class="font-medium text-gray-800 bg-gray-100 px-2 py-0.5 rounded-md inline-block">{{ $orphan->sex ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">تاريخ الميلاد</span>
                        <span class="font-medium text-gray-800">{{ $orphan->barth ? $orphan->barth->format('Y-m-d') : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">العمر الحالي</span>
                        <span class="font-medium text-gray-800">{{ $orphan->age }} سنة</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">الحالة الصحية</span>
                        <span class="font-medium {{ $orphan->health == 'جيدة' ? 'text-green-600' : 'text-red-600 font-bold' }}">{{ $orphan->health ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">المستوى الدراسي</span>
                        <span class="font-medium text-gray-800">{{ $orphan->school_level ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">طبيعة الضرر</span>
                        <span class="font-medium text-gray-800">{{ $orphan->damage ?? '-' }}</span>
                    </div>
                    <div class="sm:col-span-3 bg-indigo-50/50 p-2.5 rounded-lg border border-indigo-100/50">
                        <span class="block text-gray-500 font-medium mb-0.5">تصنيف اليتيم الخاص:</span>
                        <span class="text-indigo-900 font-bold text-xs">{{ $orphan->حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين ?? 'لا يوجد تصنيف خاص' }}</span>
                    </div>
                </div>
            </div>

            {{-- بطاقة الأب --}}
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-blue-600 border-b border-blue-50 pb-2 flex items-center gap-2">
                    <span class="w-1.5 h-3.5 bg-blue-600 rounded-sm"></span> تفاصيل بيانات الأب
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-[11px]">
                    <div class="sm:col-span-2">
                        <span class="block text-gray-400 mb-0.5">اسم الأب الكامل</span>
                        <span class="font-bold text-gray-800 text-xs">{{ $orphan->f_name ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">رقم هوية الأب</span>
                        <span class="font-medium font-mono text-gray-700">{{ $orphan->f_ssn ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">حالة الأب / سبب الوفاة</span>
                        <span class="font-medium text-gray-800">{{ $orphan->f_d ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">تاريخ الوفاة</span>
                        <span class="font-medium text-gray-800">{{ $orphan->f_date_d ? $orphan->f_date_d->format('Y-m-d') : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">طبيعة وفاة الأب (f_d_s)</span>
                        <span class="font-medium text-gray-800">{{ $orphan->f_d_s ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">عمل الأب سابقاً</span>
                        <span class="font-medium text-gray-800">{{ $orphan->f_work ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">حالة اللجوء (f_Asylum)</span>
                        <span class="font-medium text-gray-800">{{ $orphan->f_Asylum ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">الحالة الاجتماعية للأب</span>
                        <span class="font-medium text-gray-800">{{ $orphan->f_marital ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- بطاقة الأم --}}
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-emerald-600 border-b border-emerald-50 pb-2 flex items-center gap-2">
                    <span class="w-1.5 h-3.5 bg-emerald-600 rounded-sm"></span> تفاصيل بيانات الأم
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-[11px]">
                    <div class="sm:col-span-2">
                        <span class="block text-gray-400 mb-0.5">اسم الأم الكامل</span>
                        <span class="font-bold text-gray-800 text-xs">{{ $orphan->m_name ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">رقم هوية الأم</span>
                        <span class="font-medium font-mono text-gray-700">{{ $orphan->m_ssn ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">حالة الأم الحالية</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-medium {{ $orphan->m_d == 'حي' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                            {{ $orphan->m_d ?? '-' }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">تاريخ الوفاة (إن كانت متوفاة)</span>
                        <span class="font-medium text-gray-800">{{ $orphan->m_data_d ? $orphan->m_data_d->format('Y-m-d') : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">الحالة الاجتماعية للأم</span>
                        <span class="font-medium text-gray-800">{{ $orphan->m_marital ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">عمل الأم الحالي</span>
                        <span class="font-medium text-gray-800">{{ $orphan->m_work ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">رقم جوال غاز الطهي للأم</span>
                        <span class="font-medium font-mono text-gray-700">{{ $orphan->m_gas_mobile ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400 mb-0.5">إجمالي عدد أفراد الأسرة</span>
                        <span class="font-bold text-gray-900 text-xs">{{ $orphan->count_family ?? '-' }} أفراد</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- العامود الأيسر: الوكيل، معلومات السكن والاتصال والنطاق --}}
        <div class="space-y-6">
            
            {{-- بطاقة الوكيل الحاضن وعلاقة التواصل --}}
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-amber-600 border-b border-amber-50 pb-2 flex items-center gap-2">
                    <span class="w-1.5 h-3.5 bg-amber-600 rounded-sm"></span> بيانات وكيل اليتيم
                </h3>
                <div class="space-y-3 text-[11px]">
                    <div>
                        <span class="text-gray-400 block mb-0.5">اسم الوكيل الكامل</span>
                        <span class="font-bold text-gray-800">{{ $orphan->a_name ?? '-' }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-gray-400 block mb-0.5">رقم هوية الوكيل</span>
                            <span class="font-medium font-mono text-gray-700">{{ $orphan->a_ssn ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block mb-0.5">صلة القرابة</span>
                            <span class="font-medium text-gray-800">{{ $orphan->relation ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-gray-400 block mb-0.5">جنس الوكيل</span>
                            <span class="font-medium text-gray-800">{{ $orphan->a_sex ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block mb-0.5">الحالة الاجتماعية للوكيل</span>
                            <span class="font-medium text-gray-800">{{ $orphan->a_marital ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="bg-amber-50/50 p-2 rounded-lg border border-amber-100/70">
                        <span class="text-gray-500 block mb-0.5">جهة اعتماد الوكيل:</span>
                        <span class="font-medium text-amber-900">{{ $orphan->getAttribute('اعتماد الوكيل من') ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- أرقام الاتصال والنطاق الإداري --}}
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-gray-700 border-b border-gray-50 pb-2 flex items-center gap-2">
                    <span class="w-1.5 h-3.5 bg-gray-600 rounded-sm"></span> أرقام التواصل والنطاق الإداري
                </h3>
                <div class="space-y-3 text-[11px]">
                    <div>
                        <span class="text-gray-400 block mb-0.5">رقم الجوال الأساسي</span>
                        <span class="font-bold font-mono text-indigo-600 text-xs">{{ $orphan->mobile ?? 'لا يوجد' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">رقم الجوال البديل</span>
                        <span class="font-medium font-mono text-gray-600">{{ $orphan->mobile2 ?? 'لا يوجد' }}</span>
                    </div>
                    <hr class="border-gray-100">
                    <div>
                        <span class="text-gray-400 block mb-0.5">الشعبة / القسم التابع له</span>
                        <span class="font-bold text-gray-800">{{ $orphan->department?->name ?? 'غير محدد' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">المسجد المحلي</span>
                        <span class="font-bold text-gray-800">{{ $orphan->mosque?->name ?? 'غير محدد' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">مصدر البيانات في النظام</span>
                        <span class="font-medium text-gray-600 bg-gray-50 px-2 py-0.5 rounded border border-gray-100 inline-block">{{ $orphan->مصدر_البيانات ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- تفاصيل العناوين والسكن الحالي --}}
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-teal-600 border-b border-teal-50 pb-2 flex items-center gap-2">
                    <span class="w-1.5 h-3.5 bg-teal-600 rounded-sm"></span> الإقامة وبيانات السكن والنزوح
                </h3>
                <div class="space-y-3 text-[11px]">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-gray-400 block mb-0.5">المحافظة</span>
                            <span class="font-medium text-gray-800">{{ $orphan->المحافظة ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block mb-0.5">المدينة / الحي</span>
                            <span class="font-medium text-gray-800">{{ $orphan->المدينة_الحي ?? '-' }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">مكان التواجد الحالي</span>
                        <span class="font-medium text-gray-800">{{ $orphan->مكان_التواجد_الحالي ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">طبيعة الإقامة الحالية</span>
                        <span class="font-medium text-gray-800">{{ $orphan->طبيعة_الإقامة_الحالية ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">عنوان الإقامة الحالية بالتفصيل</span>
                        <span class="font-medium text-gray-800">{{ $orphan->عنوان_الإقامة_الحالية ?? '-' }}</span>
                    </div>
                    <hr class="border-gray-100">
                    <div>
                        <span class="text-gray-400 block mb-0.5">طبيعة المسكن الأصلي</span>
                        <span class="font-medium text-gray-800">{{ $orphan->طبيعة_المسكن ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">حالة المسكن الأصلي</span>
                        <span class="font-medium text-gray-800">{{ $orphan->حالة_المسكن_الأصلي ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">عنوان المسكن الأصلي قبل النزوح</span>
                        <span class="font-medium text-gray-800">{{ $orphan->عنوان_المسكن_الأصلي ?? '-' }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
<main class="flex-1 overflow-y-auto p-6 space-y-6 relative text-xs bg-gray-50/50" dir="rtl">

    {{-- ترويسة الصفحة --}}
    <div class="flex items-center justify-between bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-base font-bold text-gray-800">
                {{ $isEdit ? 'تعديل بيانات اليتيم: ' . $form->name : 'إضافة يتيم جديد للمنظومة' }}
            </h2>
            <p class="text-gray-400 text-[11px] mt-1">الرجاء ملء الحقول المطلوبة والتحقق من صحة أرقام الهويات.</p>
        </div>
        <a href="{{ route('orphan.index') }}" wire:navigate class="px-3 py-1.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-lg font-medium transition flex items-center gap-1">
            إلغاء والعودة
        </a>
    </div>

    <form wire:submit="save" class="space-y-6">
        
        {{-- القسم الأول: بيانات اليتيم الأساسية --}}
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-indigo-600 border-b border-indigo-50 pb-2 flex items-center gap-2">
                <span class="w-2 h-4 bg-indigo-600 rounded-sm"></span> بيانات اليتيم الأساسية
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block font-medium text-gray-700 mb-1">اسم اليتيم الكامل <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.blur="form.name" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs @error('form.name') border-red-500 @enderror">
                    @error('form.name') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">رقم هوية اليتيم (9 خانات) <span class="text-red-500">*</span></label>
                    <input type="number" wire:model.blur="form.SSN" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs @error('form.SSN') border-red-500 @enderror">
                    @error('form.SSN') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">تاريخ الميلاد <span class="text-red-500">*</span></label>
                    <input type="date" wire:model.live="form.barth" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs @error('form.barth') border-red-500 @enderror">
                    @error('form.barth') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">الجنس <span class="text-red-500">*</span></label>
                    <select wire:model="form.sex" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs bg-white @error('form.sex') border-red-500 @enderror">
                        <option value="">اختر الجنس</option>
                        <option value="ذكر">ذكر</option>
                        <option value="أنثى">أنثى</option>
                    </select>
                    @error('form.sex') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">الحالة الصحية <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="form.health" placeholder="جيدة، يعاني من..." class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs">
                    @error('form.health') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">المستوى التعليمي / الدراسي</label>
                    <input type="text" wire:model="form.school_level" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs">
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">طبيعة الضرر إن وجد</label>
                    <input type="text" wire:model="form.damage" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs">
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">الناجي الوحيد / يتيم الأبوين</label>
                    <input type="text" wire:model="form.حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين" class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs">
                </div>
            </div>
        </div>

        {{-- القسم الثاني: بيانات الأب --}}
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-blue-600 border-b border-blue-50 pb-2 flex items-center gap-2">
                <span class="w-2 h-4 bg-blue-600 rounded-sm"></span> بيانات الأب المتوفى
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block font-medium text-gray-700 mb-1">اسم الأب</label>
                    <input type="text" wire:model="form.f_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">هوية الأب</label>
                    <input type="number" wire:model="form.f_ssn" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">تاريخ وفاة الأب</label>
                    <input type="date" wire:model="form.f_date_d" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">سبب الوفاة</label>
                    <input type="text" wire:model="form.f_d" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">عمل الأب سابقاً</label>
                    <input type="text" wire:model="form.f_work" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">عدد أفراد العائلة</label>
                    <input type="number" wire:model="form.count_family" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
            </div>
        </div>

        {{-- القسم الثالث: بيانات الأم --}}
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-emerald-600 border-b border-emerald-50 pb-2 flex items-center gap-2">
                <span class="w-2 h-4 bg-emerald-600 rounded-sm"></span> بيانات الأم
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block font-medium text-gray-700 mb-1">اسم الأم الكامل</label>
                    <input type="text" wire:model="form.m_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">هوية الأم</label>
                    <input type="number" wire:model="form.m_ssn" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">حالة الأم (حية/متوفاة)</label>
                    <select wire:model="form.m_d" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white">
                        <option value="حي">حية</option>
                        <option value="متوفى">متوفاة</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">تاريخ وفاة الأم (إن وجد)</label>
                    <input type="date" wire:model="form.m_data_d" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">رقم جوال غاز الطهي للأم</label>
                    <input type="number" wire:model="form.m_gas_mobile" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
            </div>
        </div>

        {{-- القسم الرابع: بيانات الوكيل والمسكن والنطاق الإداري --}}
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-amber-600 border-b border-amber-50 pb-2 flex items-center gap-2">
                <span class="w-2 h-4 bg-amber-600 rounded-sm"></span> تفاصيل الوكيل والنطاق السكني والاتصال
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block font-medium text-gray-700 mb-1">اسم الوكيل</label>
                    <input type="text" wire:model="form.a_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">هوية الوكيل</label>
                    <input type="number" wire:model="form.a_ssn" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">صلة القرابة باليتيم</label>
                    <input type="text" wire:model="form.relation" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">جوال التواصل الرئيسي</label>
                    <input type="text" wire:model="form.mobile" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">جوال البديل</label>
                    <input type="text" wire:model="form.mobile2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>

                {{-- الموقع الإداري --}}
                <div>
                    <label class="block font-medium text-gray-700 mb-1">الشعبة / القسم</label>
                    <select wire:model="form.department_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white" {{ auth()->user()->view != 1 ? 'disabled' : '' }}>
                        <option value="">اختر الشعبة</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">المسجد التابع له</label>
                    <select wire:model="form.mosque_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white" {{ auth()->user()->view == 3 ? 'disabled' : '' }}>
                        <option value="">اختر المسجد</option>
                        @foreach($mosques as $mosque)
                            <option value="{{ $mosque->id }}">{{ $mosque->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">المحافظة الحالية</label>
                    <input type="text" wire:model="form.المحافظة" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">المدينة / الحي الحالي</label>
                    <input type="text" wire:model="form.المدينة_الحي" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">عنوان الإقامة الحالية بالتفصيل</label>
                    <input type="text" wire:model="form.عنوان_الإقامة_الحالية" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">مصدر البيانات</label>
                    <input type="text" wire:model="form.مصدر_البيانات" placeholder="تحديث ميداني، رابط..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                </div>
            </div>
        </div>

        {{-- أزرار التحكم بالحفظ --}}
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
            <a href="{{ route('orphan.index') }}" wire:navigate class="px-5 py-2 text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl transition font-medium">
                إلغاء التغييرات
            </a>
            <button type="submit" class="px-6 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition font-bold shadow-md flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ $isEdit ? 'تحديث البيانات الحالية' : 'اعتماد وحفظ اليتيم' }}
            </button>
        </div>
    </form>
</main>
<main class="flex-1 overflow-y-auto p-6 space-y-6 relative text-xs bg-gray-50/50" dir="rtl">

    {{-- ترويسة الصفحة --}}
    <div class="flex items-center justify-between bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-base font-bold text-gray-800">
                {{ $isEdit ? 'تعديل بيانات اليتيم: ' . $form->name : 'إضافة يتيم جديد للمنظومة' }}
            </h2>
            <p class="text-gray-400 text-[11px] mt-1">الرجاء ملء الحقول المطلوبة والتحقق من صحة أرقام الهويات.</p>
        </div>
        <a href="{{ route('orphan.index') }}" wire:navigate
            class="px-3 py-1.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-lg font-medium transition flex items-center gap-1">
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
                    <label class="block font-medium text-gray-700 mb-1">اسم اليتيم الكامل <span
                            class="text-red-500">*</span></label>
                    <input type="text" wire:model.blur="form.name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs @error('form.name') border-red-500 @enderror">
                    @error('form.name')
                        <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">رقم هوية اليتيم (9 خانات) <span
                            class="text-red-500">*</span></label>
                    <input type="number" wire:model.blur="form.SSN"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs @error('form.SSN') border-red-500 @enderror">
                    @error('form.SSN')
                        <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">تاريخ الميلاد <span
                            class="text-red-500">*</span></label>
                    <input type="date" wire:model.live="form.barth"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs @error('form.barth') border-red-500 @enderror">
                    @error('form.barth')
                        <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">الجنس <span
                            class="text-red-500">*</span></label>
                    <select wire:model="form.sex"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs bg-white @error('form.sex') border-red-500 @enderror">
                        <option value="">اختر الجنس</option>
                        <option value="ذكر">ذكر</option>
                        <option value="أنثى">أنثى</option>
                    </select>
                    @error('form.sex')
                        <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">الحالة الصحية <span
                            class="text-red-500">*</span></label>
                    <input type="text" wire:model="form.health" placeholder="جيدة، يعاني من..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs">
                    @error('form.health')
                        <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">المستوى التعليمي / الدراسي</label>
                    <input type="text" wire:model="form.school_level"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs">
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">طبيعة الضرر إن وجد</label>
                    <input type="text" wire:model="form.damage"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs">
                </div>
                {{-- بيانات المحفظة --}}
                <div>
                    <label class="block font-medium text-gray-700 mb-1">
                        رقم المحفظة
                    </label>

                    <input type="text" wire:model="form.wallet_number" placeholder="رقم المحفظة"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs @error('form.wallet_number') border-red-500 @enderror">

                    @error('form.wallet_number')
                        <span class="text-red-500 text-[10px] mt-1 block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">
                        اسم صاحب المحفظة
                    </label>

                    <input type="text" wire:model="form.wallet_owner_name" placeholder="اسم صاحب المحفظة"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs @error('form.wallet_owner_name') border-red-500 @enderror">

                    @error('form.wallet_owner_name')
                        <span class="text-red-500 text-[10px] mt-1 block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-1">
                        نوع المحفظة
                    </label>

                    <select wire:model="form.wallet_type"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs bg-white @error('form.wallet_type') border-red-500 @enderror">

                        <option value="">اختر نوع المحفظة</option>
                        <option value="بنك فلسطين">بنك فلسطين</option>
                        <option value="بال باي">بال باي</option>
                        <option value="جوال باي">جوال باي</option>
                    </select>

                    @error('form.wallet_type')
                        <span class="text-red-500 text-[10px] mt-1 block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">
                        حالة اليتيم <span class="text-red-500">*</span>
                    </label>

                    <select wire:model="form.حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg outline-none focus:border-indigo-500 text-xs bg-white @error('form.حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين') border-red-500 @enderror">
                        <option value="">اختر حالة اليتيم</option>
                        <option value="ناجي وحيد"> ناجي وحيد</option>
                        <option value="يتيم الأبوين"> يتيم الأبوين</option>
                        <option value="يتيم الأب"> يتيم الأب</option>
                        <option value="يتيم الأم">يتيم الأم</option>
                        <option value="أب مفقود">أب مفقود</option>
                    </select>

                    @error('form.حالة_اليتيم_الناجي_الوحيد_يتيم_الأبوين')
                        <span class="text-red-500 text-[10px] mt-1 block">
                            {{ $message }}
                        </span>
                    @enderror
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
                        <input type="text" wire:model="form.f_name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">هوية الأب</label>
                        <input type="number" wire:model="form.f_ssn"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">تاريخ وفاة الأب</label>
                        <input type="date" wire:model="form.f_date_d"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">سبب الوفاة</label>
                        <input type="text" wire:model="form.f_d"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">عمل الأب سابقاً</label>
                        <input type="text" wire:model="form.f_work"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">عدد أفراد العائلة</label>
                        <input type="number" wire:model="form.count_family"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
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
                        <input type="text" wire:model="form.m_name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">هوية الأم</label>
                        <input type="number" wire:model="form.m_ssn"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">حالة الأم (حية/متوفاة)</label>
                        <select wire:model="form.m_d"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white">
                            <option value="حي">حية</option>
                            <option value="متوفى">متوفاة</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">تاريخ وفاة الأم (إن وجد)</label>
                        <input type="date" wire:model="form.m_data_d"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">رقم جوال غاز الطهي للأم</label>
                        <input type="number" wire:model="form.m_gas_mobile"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
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
                        <input type="text" wire:model="form.a_name"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">هوية الوكيل</label>
                        <input type="number" wire:model="form.a_ssn"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">صلة القرابة باليتيم</label>
                        <input type="text" wire:model="form.relation"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">جوال التواصل الرئيسي</label>
                        <input type="text" wire:model="form.mobile"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">جوال البديل</label>
                        <input type="text" wire:model="form.mobile2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>

                    {{-- الموقع الإداري --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">الشعبة / القسم</label>
                        <select wire:model="form.department_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white"
                            {{ auth()->user()->view != 1 ? 'disabled' : '' }}>
                            <option value="">اختر الشعبة</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">المسجد التابع له</label>
                        <select wire:model="form.mosque_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white"
                            {{ auth()->user()->view == 3 ? 'disabled' : '' }}>
                            <option value="">اختر المسجد</option>
                            @foreach ($mosques as $mosque)
                                <option value="{{ $mosque->id }}">{{ $mosque->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">المحافظة الحالية</label>
                        <input type="text" wire:model="form.المحافظة"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">المدينة / الحي الحالي</label>
                        <input type="text" wire:model="form.المدينة_الحي"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">عنوان الإقامة الحالية بالتفصيل</label>
                        <input type="text" wire:model="form.عنوان_الإقامة_الحالية"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">مصدر البيانات</label>
                        <input type="text" wire:model="form.مصدر_البيانات" placeholder="تحديث ميداني، رابط..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs">
                    </div>
                </div>
            </div>
            {{-- القسم الخامس: صور اليتيم --}}
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm space-y-4">

                <h3 class="text-sm font-bold text-purple-600 border-b border-purple-50 pb-2 flex items-center gap-2">
                    <span class="w-2 h-4 bg-purple-600 rounded-sm"></span>
                    صور اليتيم والمستندات
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">

                    {{-- الصورة الشخصية --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">
                            الصورة الشخصية
                        </label>

                        <input type="file" wire:model="form.personal_image_file"
                            accept="image/jpeg,image/png,image/webp"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white">

                        @error('form.personal_image_file')
                            <span class="text-red-500 text-[10px] mt-1 block">
                                {{ $message }}
                            </span>
                        @enderror

                        @if ($form->personal_image_file)
                            <div class="mt-2">
                                <img src="{{ $form->personal_image_file->temporaryUrl() }}"
                                    class="w-24 h-24 object-cover rounded-lg border">
                            </div>
                        @elseif ($form->current_personal_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $form->current_personal_image) }}"
                                    class="w-24 h-24 object-cover rounded-lg border">
                            </div>
                        @endif
                    </div>


                    {{-- شهادة الميلاد --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">
                            صورة شهادة الميلاد
                        </label>

                        <input type="file" wire:model="form.birth_image_file"
                            accept="image/jpeg,image/png,image/webp"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white">

                        @error('form.birth_image_file')
                            <span class="text-red-500 text-[10px] mt-1 block">
                                {{ $message }}
                            </span>
                        @enderror

                        @if ($form->birth_image_file)
                            <div class="mt-2">
                                <img src="{{ $form->birth_image_file->temporaryUrl() }}"
                                    class="w-24 h-24 object-cover rounded-lg border">
                            </div>
                        @elseif ($form->current_birth_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $form->current_birth_image) }}"
                                    class="w-24 h-24 object-cover rounded-lg border">
                            </div>
                        @endif
                    </div>


                    {{-- صورة الأب --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">
                            صورة الأب
                        </label>

                        <input type="file" wire:model="form.father_image_file"
                            accept="image/jpeg,image/png,image/webp"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white">

                        @error('form.father_image_file')
                            <span class="text-red-500 text-[10px] mt-1 block">
                                {{ $message }}
                            </span>
                        @enderror

                        @if ($form->father_image_file)
                            <div class="mt-2">
                                <img src="{{ $form->father_image_file->temporaryUrl() }}"
                                    class="w-24 h-24 object-cover rounded-lg border">
                            </div>
                        @elseif ($form->current_father_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $form->current_father_image) }}"
                                    class="w-24 h-24 object-cover rounded-lg border">
                            </div>
                        @endif
                    </div>


                    {{-- صورة الأم --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">
                            صورة الأم
                        </label>

                        <input type="file" wire:model="form.mother_image_file"
                            accept="image/jpeg,image/png,image/webp"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white">

                        @error('form.mother_image_file')
                            <span class="text-red-500 text-[10px] mt-1 block">
                                {{ $message }}
                            </span>
                        @enderror

                        @if ($form->mother_image_file)
                            <div class="mt-2">
                                <img src="{{ $form->mother_image_file->temporaryUrl() }}"
                                    class="w-24 h-24 object-cover rounded-lg border">
                            </div>
                        @elseif ($form->current_mother_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $form->current_mother_image) }}"
                                    class="w-24 h-24 object-cover rounded-lg border">
                            </div>
                        @endif
                    </div>


                    {{-- صورة الوكيل --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">
                            صورة الوكيل
                        </label>

                        <input type="file" wire:model="form.agent_image_file"
                            accept="image/jpeg,image/png,image/webp"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white">

                        @error('form.agent_image_file')
                            <span class="text-red-500 text-[10px] mt-1 block">
                                {{ $message }}
                            </span>
                        @enderror

                        @if ($form->agent_image_file)
                            <div class="mt-2">
                                <img src="{{ $form->agent_image_file->temporaryUrl() }}"
                                    class="w-24 h-24 object-cover rounded-lg border">
                            </div>
                        @elseif ($form->current_agent_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $form->current_agent_image) }}"
                                    class="w-24 h-24 object-cover rounded-lg border">
                            </div>
                        @endif
                    </div>

                </div>

                <div class="text-[10px] text-gray-400">
                    الصيغ المسموحة: JPG, JPEG, PNG, WEBP — الحد الأقصى 5MB لكل صورة.
                </div>

            </div>
            {{-- أزرار التحكم بالحفظ --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('orphan.index') }}" wire:navigate
                    class="px-5 py-2 text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl transition font-medium">
                    إلغاء التغييرات
                </a>
                <button type="submit" wire:loading.attr="disabled"
                    class="px-6 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition font-bold shadow-md flex items-center gap-2 disabled:opacity-50">
                    <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>

                    <svg wire:loading class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>

                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                        </path>
                    </svg>

                    <span wire:loading.remove>
                        {{ $isEdit ? 'تحديث البيانات الحالية' : 'اعتماد وحفظ اليتيم' }}
                    </span>

                    <span wire:loading>
                        جاري الحفظ...
                    </span>
                </button>
            </div>
    </form>
</main>

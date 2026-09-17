<main class="flex-1 overflow-y-auto p-6 space-y-6 relative">

    {{-- رسالة النجاح --}}
    @if (session()->has('message'))
        <div
            class="p-4 text-sm text-green-800 bg-green-50 rounded-xl border border-green-200 flex items-center justify-between shadow-sm"
        >
            <span class="font-medium">
                {{ session('message') }}
            </span>
        </div>
    @endif

    {{-- عنوان الصفحة --}}
    <div
        class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100"
    >
        <div class="flex items-center gap-4">

            {{-- أيقونة المستخدم --}}
            <div
                class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 11a4 4 0 100-8 4 4 0 000 8zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"
                    />
                </svg>
            </div>

            <div>
                <h1 class="text-xl font-bold text-gray-900">
                    الملف الشخصي
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    عرض بيانات الحساب وتعديل البيانات الشخصية
                </p>
            </div>

        </div>
    </div>


    {{-- محتوى الصفحة --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- البيانات غير القابلة للتعديل --}}
        <div
            class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
        >

            <div class="p-5 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-800">
                    بيانات الحساب
                </h2>

                <p class="text-xs text-gray-400 mt-1">
                    هذه البيانات لا يمكن تعديلها من الملف الشخصي
                </p>
            </div>

            <div class="p-5 space-y-4">

                {{-- الاسم --}}
                <div>
                    <label class="block font-medium text-gray-500 text-xs mb-1">
                        الاسم الكامل
                    </label>

                    <div
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-sm text-gray-800"
                    >
                        {{ $user->name ?: '—' }}
                    </div>
                </div>

                {{-- الهوية --}}
                <div>
                    <label class="block font-medium text-gray-500 text-xs mb-1">
                        رقم الهوية (SSN)
                    </label>

                    <div
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-sm text-gray-800"
                    >
                        {{ $user->ssn ?: '—' }}
                    </div>
                </div>

                {{-- الدور --}}
                <div>
                    <label class="block font-medium text-gray-500 text-xs mb-1">
                        الدور الوظيفي
                    </label>

                    <div class="p-2.5">
                        <span
                            class="bg-purple-50 text-purple-700 text-xs px-2.5 py-1 rounded-lg font-medium"
                        >
                            {{ $user->role ?: '—' }}
                        </span>
                    </div>
                </div>

                {{-- الحالة --}}
                <div>
                    <label class="block font-medium text-gray-500 text-xs mb-1">
                        حالة المستخدم
                    </label>

                    <div class="p-2.5">
                        @if ($user->active == 1)
                            <span
                                class="bg-green-50 border border-green-200 text-green-700 text-xs px-2.5 py-1 rounded-full font-semibold"
                            >
                                نشط
                            </span>
                        @else
                            <span
                                class="bg-red-50 border border-red-200 text-red-700 text-xs px-2.5 py-1 rounded-full font-semibold"
                            >
                                غير نشط
                            </span>
                        @endif
                    </div>
                </div>

                {{-- القسم --}}
                <div>
                    <label class="block font-medium text-gray-500 text-xs mb-1">
                        الشعبة
                    </label>

                    <div
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-sm text-gray-800"
                    >
                        {{ $user->department->name ?? '—' }}
                    </div>
                </div>

                {{-- المسجد --}}
                <div>
                    <label class="block font-medium text-gray-500 text-xs mb-1">
                        المسجد
                    </label>

                    <div
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-sm text-gray-800"
                    >
                        {{ $user->mosque->name ?? '—' }}
                    </div>
                </div>

                {{-- مستوى العرض --}}
                <div>
                    <label class="block font-medium text-gray-500 text-xs mb-1">
                        مستوى العرض
                    </label>

                    <div
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 text-sm text-gray-800"
                    >
                        @switch($user->view)
                            @case(1)
                                محلية
                                @break

                            @case(2)
                                شعبة
                                @break

                            @case(3)
                                مسجد
                                @break

                            @default
                                —
                        @endswitch
                    </div>
                </div>

            </div>
        </div>


        {{-- البيانات القابلة للتعديل --}}
        <div
            class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden lg:col-span-2"
        >

            <div class="p-5 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-800">
                    تعديل البيانات الشخصية
                </h2>

                <p class="text-xs text-gray-400 mt-1">
                    يمكنك تعديل البيانات الموجودة في هذا القسم فقط
                </p>
            </div>

            <form
                wire:submit.prevent="updateProfile"
                class="p-6 space-y-4"
            >

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- اسم المستخدم --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">
                            اسم المستخدم
                        </label>

                        <input
                            type="text"
                            wire:model="username"
                            class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10"
                        >

                        @error('username')
                            <span class="text-red-500 text-[11px] mt-1 block">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>


                    {{-- الهاتف --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">
                            رقم الهاتف
                        </label>

                        <input
                            type="text"
                            wire:model="phone"
                            placeholder="05xxxxxxxx"
                            class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10"
                        >

                        @error('phone')
                            <span class="text-red-500 text-[11px] mt-1 block">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>


                    {{-- الواتساب --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">
                            رقم الواتساب
                        </label>

                        <input
                            type="text"
                            wire:model="whatsapp"
                            placeholder="0097xxxxxxxxxx"
                            class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10"
                        >

                        @error('whatsapp')
                            <span class="text-red-500 text-[11px] mt-1 block">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>


                    {{-- كلمة المرور --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">
                            كلمة المرور الجديدة
                        </label>

                        <input
                            type="password"
                            wire:model="password"
                            autocomplete="new-password"
                            placeholder="اتركها فارغة لعدم التغيير"
                            class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10"
                        >

                        @error('password')
                            <span class="text-red-500 text-[11px] mt-1 block">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>


                    {{-- تأكيد كلمة المرور --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">
                            تأكيد كلمة المرور
                        </label>

                        <input
                            type="password"
                            wire:model="password_confirmation"
                            autocomplete="new-password"
                            placeholder="أعد كتابة كلمة المرور"
                            class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10"
                        >
                    </div>

                </div>


                {{-- العنوان --}}
                <div>
                    <label class="block font-medium text-gray-700 mb-1">
                        العنوان السكني
                    </label>

                    <textarea
                        wire:model="address"
                        rows="3"
                        class="w-full border border-gray-300 rounded-lg p-2.5 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 resize-none"
                    ></textarea>

                    @error('address')
                        <span class="text-red-500 text-[11px] mt-1 block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>


                {{-- أزرار التحكم --}}
                <div
                    class="border-t border-gray-100 pt-4 flex justify-end bg-gray-50 -mx-6 -mb-6 p-4"
                >
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition cursor-pointer disabled:opacity-50"
                    >
                        <span wire:loading.remove>
                            حفظ التعديلات
                        </span>

                        <span wire:loading>
                            جارٍ الحفظ...
                        </span>
                    </button>
                </div>

            </form>

        </div>

    </div>

</main>

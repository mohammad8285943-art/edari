<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة النظام الآمنة - جمعية غزة الخيرية</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 h-screen w-screen flex items-center justify-center p-4 relative overflow-hidden">

    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-emerald-100 rounded-full filter blur-3xl opacity-70"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-50 rounded-full filter blur-3xl opacity-70"></div>
    </div>

    <div class="relative z-10 w-full max-w-md bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-8 sm:p-10">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-100 text-2xl font-bold mb-3 shadow-sm">
                MM
            </div>
            <h1 class="text-2xl font-bold text-gray-800">تسجيل الدخول</h1>
            <p class="text-sm text-gray-500 mt-1">جمعية غزة الخيرية</p>
        </div>

        <form id="secure-login-form" class="space-y-5" method="post" action="{{ route('login') }}">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">اسم المستخدم</label>
                <input
                    type="text"
                    required
                    name="username"
                    placeholder="أدخل اسم المستخدم"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition duration-200"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">كلمة المرور</label>
                <input
                    type="password"
                    required
                    placeholder="••••••••"
                    name="password"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition duration-200"
                >
            </div>

            <button
                type="submit"
                class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-medium py-3 rounded-xl shadow-md shadow-emerald-600/20 transition-all duration-200 flex justify-center items-center gap-2 mt-2 cursor-pointer"
            >
                <span>تسجيل الدخول</span>
            </button>
        </form>

    </div>

</body>
</html>

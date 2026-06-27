<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة النظام الآمنة - جمعية غزة الخيرية</title>
    <!-- استدعاء Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- خط تجوالي -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    {{-- <style>
        body {
            font-family: 'Tajawal', sans-serif;
            perspective: 1000px; /* لتمكين التفاعل ثلاثي الأبعاد */
        }
        .tilt-card {
            transform-style: preserve-3d;
            transition: transform 0.1s ease-out, box-shadow 0.3s ease;
        }
        /* حركة نبض خفيفة للخلفية الهلامية */
        .blob {
            animation: pulseBlob 8s infinite alternate;
        }
        @keyframes pulseBlob {
            0% { transform: scale(1) translate(0px, 0px); }
            100% { transform: scale(1.2) translate(30px, -20px); }
        }
    </style> --}}
</head>
<body id="scene" class="bg-amber-200 h-screen w-screen flex items-center justify-center overflow-hidden relative select-none">

    <!-- الخلفية السينمائية العميقة -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-tr from-gray-700  to-white"></div>

        <!-- كرات إضاءة هلامية متحركة (Blobs) تضيف عمقاً خلف البطاقة -->
        <div class="blob absolute top-1/4 left-1/4 w-96 h-96 bg-charity-700/20 rounded-full filter blur-3xl pointer-events-none"></div>
        <div class="blob absolute bottom-1/4 right-1/4 w-96 h-96 bg-emerald-600/10 rounded-full filter blur-3xl pointer-events-none" style="animation-delay: -4s;"></div>
    </div>

    <!-- البطاقة العائمة الذكية (The 3D Floating Card) -->
    <div id="card" class="tilt-card relative z-10 w-full max-w-md mx-4 bg-white/[0.04] backdrop-blur-xl rounded-3xl border border-white/10 shadow-[0_25px_50px_-12px_rgba(0,0,0,0.7)] p-8 md:p-10 flex flex-col justify-center text-white">

        <!-- الشعار والهوية -->
        <div class="text-center mb-8" style="transform: translateZ(50px);">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-400 rounded-2xl border border-white/10 text-3xl mb-4 shadow-inner">
                MM
            </div>
            <h1 class="text-2xl font-bold text-white tracking-wide">تسجيل الدخول</h1>
        </div>

        <!-- نموذج تسجيل الدخول -->
        <form id="secure-login-form" class="space-y-5" style="transform: translateZ(40px);" method="post" action="{{ route('login') }}">
            <div>
                <label class="block text-xs font-medium text-black mb-1.5 mr-1">اسم المستخدم</label>
                <div class="relative">
                    <input type="text" required name="username"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-charity-600 focus:border-transparent transition-all duration-300">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-black mb-1.5 mr-1">كلمة المرور</label>
                <input type="password" required placeholder="••••••••" name="password"
                    class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-indigo-600 focus:outline-none focus:ring-2 focus:ring-charity-600 focus:border-transparent transition-all duration-300">
            </div>

            <!-- زر الدخول -->
            <button type="submit" class="w-full bg-gray-400 hover:bg-charity-700 text-white font-bold py-3 rounded-xl transition duration-300 shadow-lg shadow-charity-600/20 flex justify-center items-center gap-2 mt-4 active:scale-95 transform">
                <span>تسجيل دخول</span>
            </button>
        </form>

        <!-- واجهة النجاح المدمجة الديناميكية -->
        <div id="success-overlay" class="absolute inset-0 bg-gray-950/90 backdrop-blur-md rounded-3xl flex flex-col items-center justify-center p-8 text-center hidden z-30">
            <div class="w-16 h-16 bg-charity-600/20 text-charity-600 rounded-full flex items-center justify-center text-3xl mb-4 animate-scale">
                ✓
            </div>
            <h3 class="text-xl font-bold mb-1">تم التحقق من الهوية</h3>
            <p class="text-xs text-gray-400 mb-4">جاري مزامنة الملفات الميدانية والبيانات...</p>
            <div class="w-12 h-1 border-2 border-charity-600 border-t-transparent rounded-full animate-spin"></div>
        </div>
    </div>

    <!-- جافا سكريبت للحركة ثلاثية الأبعاد والتفاعل الديناميكي -->
    {{-- <script>
        const scene = document.getElementById('scene');
        const card = document.getElementById('card');
        const form = document.getElementById('secure-login-form');
        const successOverlay = document.getElementById('success-overlay');

        // الحسابات الرياضية لتأثير الـ 3D Tilt عند حركة الماوس
        scene.addEventListener('mousemove', (e) => {
            const xAxis = (window.innerWidth / 2 - e.pageX) / 25; // نسبة الميل الأفقي
            const yAxis = (window.innerHeight / 2 - e.pageY) / 25; // نسبة الميل العمودي

            card.style.transform = `rotateY(${xAxis}deg) rotateX(${-yAxis}deg)`;
        });

        // إعادة البطاقة لوضعها الطبيعي المستقر عند خروج الماوس
        scene.addEventListener('mouseleave', () => {
            card.style.transform = `rotateY(0deg) rotateX(0deg)`;
            card.style.transition = "transform 0.5s ease";
        });

        scene.addEventListener('mouseenter', () => {
            card.style.transition = "none";
        });

        // تشغيل تأثير واجهة التحقق عند الضغط على الدخول
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            successOverlay.classList.remove('hidden');
            form.reset();
        });
    </script> --}}
</body>
</html>

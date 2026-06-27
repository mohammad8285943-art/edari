<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بورتفوليو سارة أحمد</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <!-- Hero Section -->
    <section class="max-w-5xl mx-auto px-6 py-20 text-center">
        <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">أهلاً، أنا سارة. أحوّل الأفكار المعقدة إلى واجهات رقمية بسيطة.</h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-8">مطورة واجهات مستخدم شغوفة بتجربة المستخدم. أساعد الشركات الناشئة على بناء مواقع سريعة وجذابة.</p>
        <div class="flex justify-center gap-4">
            <a href="#projects" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-indigo-700 transition">تصفّح أعمالي</a>
            <a href="#contact" class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition">تواصل معي</a>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="max-w-5xl mx-auto px-6 py-16">
        <h2 class="text-3xl font-bold text-gray-900 mb-10 text-center">أبرز المشاريع</h2>
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Project 1 -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">منصة "مؤشر" لإدارة المال</h3>
                    <p class="text-gray-600 mb-4">تطبيق ويب يساعد الأفراد على تتبع مصاريفهم اليومية وتحليلها عبر رسوم بيانية تفاعلية.</p>
                    <span class="inline-block bg-indigo-50 text-indigo-700 text-sm px-3 py-1 rounded-full font-medium mb-4">React.js · Tailwind</span>
                    <div class="flex gap-4">
                        <a href="#" class="text-indigo-600 font-medium hover:underline">رابط المشروع ←</a>
                    </div>
                </div>
            </div>
            <!-- Project 2 -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">متجر "تَمرة" الإلكتروني</h3>
                    <p class="text-gray-600 mb-4">متجر متكامل لبيع المنتجات العضوية، يتميز بسرعته الفائقة وتجربة الشراء بضغطة واحدة.</p>
                    <span class="inline-block bg-indigo-50 text-indigo-700 text-sm px-3 py-1 rounded-full font-medium mb-4">Next.js · Shopify</span>
                    <div class="flex gap-4">
                        <a href="#" class="text-indigo-600 font-medium hover:underline">رابط المشروع ←</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="bg-indigo-900 text-white py-16 text-center">
        <div class="max-w-3xl mx-auto px-6">
            <h2 class="text-3xl font-bold mb-4">هل لديك مشروع قادم؟ لنبنيه معاً!</h2>
            <p class="text-indigo-200 mb-8">أنا متاحة حالياً للمشاريع الحرة. أرسل لي رسالة وسأرد عليك في أقرب وقت.</p>
            <a href="mailto:sara@example.com" class="bg-white text-indigo-900 px-8 py-3 rounded-lg font-bold hover:bg-indigo-50 transition">إرسال بريد إلكتروني</a>
        </div>
    </section>

</body>
</html>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>زيتون الأصيل | إنتاج زيتون فاخر</title>
    <style>
        /* إعدادات عامة */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f2e9;
            color: #2e3b2e;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* خلفية متدرجة ناعمة */
        .page-wrapper {
            background: linear-gradient(145deg, #f9f7f0 0%, #e8e3d3 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            position: relative;
            overflow: hidden;
        }

        /* زخارف خلفية - أوراق شجر متحركة */
        .leaf-bg {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .leaf {
            position: absolute;
            background: rgba(86, 125, 70, 0.08);
            border-radius: 0 70% 0 70%;
            transform: rotate(45deg);
            animation: floatLeaf 18s infinite ease-in-out;
        }

        .leaf:nth-child(1) {
            width: 180px;
            height: 180px;
            top: 5%;
            left: -3%;
            animation-duration: 22s;
        }

        .leaf:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: -2%;
            animation-duration: 19s;
            animation-delay: -3s;
            background: rgba(86, 125, 70, 0.06);
        }

        .leaf:nth-child(3) {
            width: 250px;
            height: 250px;
            bottom: -5%;
            left: 10%;
            animation-duration: 25s;
            animation-delay: -7s;
            background: rgba(107, 142, 87, 0.05);
        }

        .leaf:nth-child(4) {
            width: 90px;
            height: 90px;
            top: 20%;
            right: 15%;
            animation-duration: 15s;
            animation-delay: -2s;
            background: rgba(86, 125, 70, 0.07);
        }

        .leaf:nth-child(5) {
            width: 200px;
            height: 200px;
            bottom: 10%;
            right: 5%;
            animation-duration: 28s;
            animation-delay: -10s;
            background: rgba(107, 142, 87, 0.04);
        }

        @keyframes floatLeaf {
            0% {
                transform: rotate(45deg) translate(0, 0) scale(1);
                opacity: 0.3;
            }

            25% {
                transform: rotate(55deg) translate(25px, -20px) scale(1.02);
                opacity: 0.5;
            }

            50% {
                transform: rotate(40deg) translate(-15px, 30px) scale(0.98);
                opacity: 0.4;
            }

            75% {
                transform: rotate(60deg) translate(20px, -10px) scale(1.01);
                opacity: 0.55;
            }

            100% {
                transform: rotate(45deg) translate(0, 0) scale(1);
                opacity: 0.3;
            }
        }

        /* حاوية المحتوى الرئيسي */
        .content {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            width: 100%;
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 3.5rem;
        }

        /* أنيميشن ظهور تدريجي */
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 1s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }

        .fade-up-1 {
            animation-delay: 0.2s;
        }

        .fade-up-2 {
            animation-delay: 0.5s;
        }

        .fade-up-3 {
            animation-delay: 0.8s;
        }

        .fade-up-4 {
            animation-delay: 1.1s;
        }

        .fade-up-5 {
            animation-delay: 1.4s;
        }

        .fade-up-6 {
            animation-delay: 1.7s;
        }

        @keyframes fadeUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* الشعار */
        .logo-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .logo-icon {
            font-size: 4.5rem;
            line-height: 1;
            filter: drop-shadow(0 8px 12px rgba(60, 80, 40, 0.2));
            animation: gentlePulse 3s infinite ease-in-out;
        }

        @keyframes gentlePulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .logo-text {
            font-size: 3.2rem;
            font-weight: 800;
            letter-spacing: -1px;
            color: #3b5e2b;
            text-shadow: 2px 2px 0 rgba(190, 210, 160, 0.5);
        }

        .logo-text span {
            color: #7a9e5b;
            font-weight: 300;
        }

        .tagline {
            font-size: 1.3rem;
            color: #5b6e4b;
            font-weight: 400;
            letter-spacing: 2px;
            border-bottom: 2px solid #c3d6b0;
            padding-bottom: 0.75rem;
            display: inline-block;
        }

        /* شبكة المميزات */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 2rem;
            margin: 1rem 0;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            padding: 2rem 1.5rem;
            border-radius: 40px 12px 40px 12px;
            box-shadow: 0 12px 28px -8px rgba(60, 80, 40, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.9);
            transition: all 0.35s cubic-bezier(0.23, 1, 0.32, 1);
            opacity: 0;
            animation: fadeUp 0.9s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }

        .feature-card:nth-child(1) {
            animation-delay: 0.6s;
        }

        .feature-card:nth-child(2) {
            animation-delay: 0.9s;
        }

        .feature-card:nth-child(3) {
            animation-delay: 1.2s;
        }

        .feature-card:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: 0 22px 38px -12px rgba(60, 80, 40, 0.25);
            background: rgba(255, 255, 255, 0.85);
        }

        .feature-icon {
            font-size: 2.8rem;
            margin-bottom: 1rem;
            display: block;
        }

        .feature-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2f4b1f;
            margin-bottom: 0.5rem;
        }

        .feature-desc {
            font-size: 0.95rem;
            color: #4e5e3e;
            line-height: 1.7;
        }

        /* قسم الإحصائيات - بيانات افتراضية */
        .stats-section {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2.5rem 4rem;
            background: rgba(235, 245, 225, 0.7);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            padding: 2.5rem 3rem;
            border-radius: 120px 20px 120px 20px;
            margin: 1rem 0;
            border: 1px solid rgba(190, 210, 160, 0.6);
            box-shadow: inset 0 1px 8px rgba(255, 255, 255, 0.8), 0 15px 25px -10px rgba(60, 80, 40, 0.2);
            opacity: 0;
            animation: fadeUp 1s cubic-bezier(0.23, 1, 0.32, 1) forwards;
            animation-delay: 1.0s;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 140px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: #3b5e2b;
            line-height: 1.2;
            letter-spacing: -1px;
            background: linear-gradient(135deg, #4e6e3a, #6e8e4e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            font-size: 1.1rem;
            color: #4e5e3e;
            font-weight: 500;
            margin-top: 0.25rem;
            letter-spacing: 0.5px;
        }

        /* شريط الفواصل */
        .divider {
            width: 80px;
            height: 3px;
            background: #b8d0a0;
            border-radius: 10px;
            margin: 0.5rem auto;
            opacity: 0.7;
        }

        /* قسم القصة / النص التعريفي */
        .story-section {
            max-width: 750px;
            margin: 0 auto;
            font-size: 1.15rem;
            color: #3d4d2d;
            line-height: 2;
            background: rgba(255, 255, 255, 0.5);
            padding: 2rem 2.5rem;
            border-radius: 60px 20px 60px 20px;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border: 1px solid rgba(210, 225, 190, 0.7);
            box-shadow: 0 8px 18px -6px rgba(60, 80, 40, 0.1);
            opacity: 0;
            animation: fadeUp 1s cubic-bezier(0.23, 1, 0.32, 1) forwards;
            animation-delay: 1.3s;
        }

        .story-section p {
            margin-bottom: 1rem;
        }

        .story-section p:last-child {
            margin-bottom: 0;
            font-style: italic;
            color: #5a6e4a;
        }

        /* تذييل بدون تواصل */
        .footer-note {
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #8a9e7a;
            letter-spacing: 1px;
            opacity: 0;
            animation: fadeUp 1s cubic-bezier(0.23, 1, 0.32, 1) forwards;
            animation-delay: 1.8s;
        }

        /* استجابة للشاشات الصغيرة */
        @media (max-width: 700px) {
            .logo-text {
                font-size: 2.4rem;
            }

            .tagline {
                font-size: 1rem;
            }

            .stats-section {
                gap: 1.5rem 2rem;
                padding: 2rem 1.5rem;
                border-radius: 40px 12px 40px 12px;
            }

            .stat-number {
                font-size: 2.2rem;
            }

            .stat-label {
                font-size: 0.9rem;
            }

            .story-section {
                padding: 1.5rem;
                font-size: 1rem;
            }

            .feature-card {
                padding: 1.5rem 1rem;
            }

            .feature-title {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 450px) {
            .page-wrapper {
                padding: 1.5rem 1rem;
            }

            .logo-icon {
                font-size: 3.2rem;
            }

            .logo-text {
                font-size: 1.8rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .stats-section {
                flex-direction: column;
                gap: 1.2rem;
            }
        }

        /* منع أي تفاعل مع النقر (اختياري، لكنه يدعم فكرة عدم وجود أزرار) */
        .no-click {
            user-select: none;
            -webkit-user-select: none;
        }
    </style>
</head>

<body>
    <div class="page-wrapper no-click">
        <!-- أوراق خلفية متحركة -->
        <div class="leaf-bg">
            <div class="leaf"></div>
            <div class="leaf"></div>
            <div class="leaf"></div>
            <div class="leaf"></div>
            <div class="leaf"></div>
        </div>

        <!-- المحتوى الرئيسي -->
        <div class="content">

            <!-- الشعار والعنوان -->
            <div class="logo-area fade-up fade-up-1">
                <div class="logo-icon">🫒</div>
                <h1 class="logo-text">زيتون <span>الأصيل</span></h1>
                <div class="tagline">من قلب الأرض إلى مائدتك</div>
            </div>

            <!-- المميزات -->
            <div class="features-grid">
                <div class="feature-card">
                    <span class="feature-icon">🌿</span>
                    <h3 class="feature-title">زراعة عضوية</h3>
                    <p class="feature-desc">أشجار زيتون معمرة تُروى بمياه الأمطار وتُغذى من تربة طبيعية خصبة.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">☀️</span>
                    <h3 class="feature-title">قطف يدوي</h3>
                    <p class="feature-desc">يتم قطف حبات الزيتون يدوياً في ذروة نضجها للحفاظ على الجودة.</p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">🫒</span>
                    <h3 class="feature-title">زيت بكر ممتاز</h3>
                    <p class="feature-desc">عصرة أولى على البارد، بنكهة غنية ورائحة فواحة لا تُقاوم.</p>
                </div>
            </div>

            <!-- الإحصائيات (بيانات افتراضية) -->
            <div class="stats-section">
                <div class="stat-item">
                    <span class="stat-number">١٨٠</span>
                    <span class="stat-label">دونم مزروع</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">٤٢٠٠</span>
                    <span class="stat-label">شجرة زيتون</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">٩٥</span>
                    <span class="stat-label">طن إنتاج سنوي</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">٧٠</span>
                    <span class="stat-label">عاماً من الخبرة</span>
                </div>
            </div>

            <!-- قصة المزرعة -->
            <div class="story-section">
                <p>منذ سبعين عاماً، توارثت عائلتنا زراعة الزيتون في تلالنا المشمسة. كل شجرة تحكي قصة صبرٍ وأصالة، وكل
                    قطرة زيت تحمل عبق الأرض ودفء الشمس.</p>
                <p>نؤمن أن الزيتون ليس مجرد محصول، بل إرثٌ وثقافةٌ تُروى عبر الأجيال. من أرضنا الطيبة إلى بيوتكم، نقدم
                    لكم خلاصة الكرم والجودة.</p>
            </div>

            <!-- تذييل بدون أي تواصل -->
            <div class="footer-note">
                <span>© زيتون الأصيل — إرثٌ من الأرض</span>
            </div>

        </div>
    </div>
</body>

</html>

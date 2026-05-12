<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $activeContests app\models\Konkurs[] */
/** @var $recentWorks app\models\Submission[] */
/** @var $recommended app\models\Submission[] */
/** @var $popularNominations app\models\Nomination[] */
/** @var $stats array */
/** @var $popularContests array */

$this->title = 'PhotoContest — Платформа фотоконкурсов';
?><?php
// 🔥 Функция для русского формата дат (работает БЕЗ расширения intl)
if (!function_exists('ruDate')) {
    function ruDate($date) {
        $months = [
            1=>'января', 2=>'февраля', 3=>'марта', 4=>'апреля', 5=>'мая', 6=>'июня',
            7=>'июля', 8=>'августа', 9=>'сентября', 10=>'октября', 11=>'ноября', 12=>'декабря'
        ];
        $ts = is_numeric($date) ? (int)$date : strtotime($date);
        if (!$ts) return $date;
        $d = date('j', $ts);
        $m = $months[(int)date('n', $ts)];
        $y = date('Y', $ts);
        return "$d $m $y";
    }
}
?>
<!DOCTYPE html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Html::encode($this->title) ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bg': '#f7f6f9',
                        'surface': '#ffffff',
                        'border': '#e5e3eb',
                        'text': '#111118',
                        'text-muted': '#6b6b80',
                        'accent': '#8b77b3',
                        'accent-hover': '#75639c',
                        'accent-light': '#f0eaf5',
                        'accent-soft': '#e8e0f2',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    keyframes: {
                        float: { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-14px)' } },
                        floatAlt: { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(10px)' } },
                        pulse: { '0%,100%': { opacity: '1' }, '50%': { opacity: '0.7' } },
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --ease: cubic-bezier(0.16, 1, 0.3, 1);
            --spring: cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        * { box-sizing: border-box; }
        body {
            background: #f7f6f9;
            color: #111118;
            font-family: 'Inter', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        body::after {
            content: '';
            position: fixed; inset: 0;
            background-image: url("image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 9999; mix-blend-mode: multiply;
        }

        .rv { opacity: 0; transform: translateY(20px); transition: opacity 0.8s var(--ease), transform 0.8s var(--ease); }
        .rv.active { opacity: 1; transform: translateY(0); }
        .d1 { transition-delay: 0.1s; }
        .d2 { transition-delay: 0.2s; }
        .d3 { transition-delay: 0.3s; }

        .phone {
            width: 260px;
            background: #1a1a22;
            border-radius: 36px;
            padding: 8px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
            backface-visibility: hidden; /* Убирает артефакты 3D-рендеринга */
            box-shadow:
                    0 32px 64px rgba(0,0,0,0.2),
                    0 12px 24px rgba(0,0,0,0.1),
                    inset 0 1px 0 rgba(255,255,255,0.15),
                    0 0 0 0.5px rgba(0,0,0,0.4);
            position: absolute;
            z-index: 2;
            transform-style: preserve-3d;
            transition: transform 0.4s var(--ease), box-shadow 0.4s var(--ease);
            will-change: transform;
        }
        .phone:hover {
            transform: translateY(-50%) perspective(1200px) scale(1.035);
            box-shadow:
                    0 40px 80px rgba(0,0,0,0.25),
                    0 16px 32px rgba(0,0,0,0.12),
                    inset 0 1px 0 rgba(255,255,255,0.2),
                    0 0 0 0.5px rgba(0,0,0,0.5);
            z-index: 10;
            animation-play-state: paused; /* Пауза анимации float */
            cursor: default;
        }

        /* Плавная пауза анимации */
        @supports (animation-play-state: paused) {
            .phone { animation-play-state: running; }
            .phone:hover { animation-play-state: paused; }
        }
        .phone-screen {
            background: #fafaf8;
            border-radius: 28px;
            overflow: hidden;
            aspect-ratio: 9/19.5;
            position: relative;
            -webkit-font-smoothing: subpixel-antialiased;
            text-rendering: geometricPrecision;
            transform: translateZ(0); /* «Закрепляет» слой для чёткости */
        }
        .phone-notch {
            position: absolute;
            top: 8px;
            left: 50%;
            transform: translateX(-50%);
            width: 85px;
            height: 24px;
            background: #1a1a22;
            border-radius: 16px;
            z-index: 20;
        }
        .phone-homebar {
            position: absolute;
            bottom: 8px;
            left: 50%;
            transform: translateX(-50%);
            width: 110px;
            height: 4px;
            background: rgba(0,0,0,0.12);
            border-radius: 4px;
            z-index: 20;
        }
        .phone-statusbar {
            position: absolute;
            top: 10px;
            left: 20px;
            right: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 15;
        }
        .phone-statusbar span {
            font-size: 10px;
            font-weight: 600;
            color: #fff;
        }
        .phone-statusbar svg {
            width: 14px;
            height: 14px;
            color: #fff;
        }

        .mockup-left {
            left: 3%;
            top: 50%;
            transform: translateY(-50%) perspective(1200px) rotateY(10deg) rotateX(-1.5deg);
            animation: float 10s ease-in-out infinite;
        }
        .mockup-right {
            right: 3%;
            top: 48%;
            transform: translateY(-50%) perspective(1200px) rotateY(-8deg) rotateX(1.5deg);
            animation: floatAlt 11s ease-in-out infinite;
            animation-delay: -3s;
        }

        @media (max-width: 1280px) {
            .phone { width: 220px; }
            .mockup-left { left: 1%; }
            .mockup-right { right: 1%; }
        }
        @media (max-width: 1100px) {
            .phone, .mockup-left, .mockup-right { display: none !important; }
        }

        .ui-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 0 0 1px rgba(0,0,0,0.04);
        }
        .ui-card-gradient {
            width: 100%;
            height: 80px;
            position: relative;
        }
        .ui-avatar {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            font-weight: 700;
            color: #fff;
        }
        .ui-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 6px;
            font-size: 7px;
            font-weight: 600;
        }
        .ui-btn {
            display: block;
            width: 100%;
            padding: 7px;
            border-radius: 8px;
            font-size: 9px;
            font-weight: 600;
            text-align: center;
            color: #fff;
            background: #111;
        }
        .ui-btn-outline {
            background: transparent;
            color: #111;
            border: 1px solid #e5e3eb;
        }
        .ui-stars {
            display: flex;
            gap: 1px;
        }
        .ui-stars svg { width: 9px; height: 9px; }

        .floating-card {
            position: absolute;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08), 0 0 0 1px rgba(0,0,0,0.04);
            padding: 10px 14px;
            font-size: 10px;
            z-index: 5;
            backdrop-filter: blur(12px);
            background: rgba(255,255,255,0.9);
            animation: float 7s ease-in-out infinite;
        }

        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 13px 26px; border-radius: 12px;
            font-weight: 600; font-size: 0.9rem;
            transition: all 0.3s var(--ease); text-decoration: none; cursor: pointer;
        }
        .btn-primary { background: #111; color: #fff; }
        .btn-primary:hover { background: #222; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.15); }
        .btn-outline { background: transparent; color: #111; border: 1.5px solid #e5e3eb; }
        .btn-outline:hover { border-color: #8b77b3; background: #f0eaf5; transform: translateY(-2px); }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 7px 15px; background: rgba(255,255,255,0.7);
            backdrop-filter: blur(10px); border: 1px solid rgba(139,119,179,0.15);
            border-radius: 100px; font-size: 0.8rem; font-weight: 500; color: #6b6b80;
        }

        .card-pro {
            background: #fff; border: 1px solid #e5e3eb; border-radius: 18px;
            padding: 22px; transition: transform 0.35s var(--ease), box-shadow 0.35s var(--ease);
            display: flex; flex-direction: column;
        }
        .card-pro:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,0.06); }
        .badge-pro {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 4px 10px; border-radius: 999px;
            background: #f0eaf5; color: #75639c; font-size: 0.68rem; font-weight: 600;
        }

        .works-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 18px; }
        .work-card {
            background: #fff; border: 1px solid #e5e3eb; border-radius: 16px;
            overflow: hidden; transition: transform 0.35s var(--ease), box-shadow 0.35s var(--ease);
        }
        .work-card:hover { transform: translateY(-5px); box-shadow: 0 18px 36px rgba(0,0,0,0.08); }
        .work-img { overflow: hidden; aspect-ratio: 1; background: #f0eaf5; }
        .work-img img { transition: transform 0.5s var(--ease); }
        .work-card:hover .work-img img { transform: scale(1.06); }

        .cursor-glow {
            position: fixed; top: 0; left: 0; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(139,119,179,0.07) 0%, transparent 65%);
            pointer-events: none; z-index: 0; transform: translate(-50%, -50%); will-change: transform;
        }
        @media (max-width: 1100px) { .cursor-glow { display: none; } }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f7f6f9; }
        ::-webkit-scrollbar-thumb { background: #d5d0e0; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #b5adc5; }

        @media (prefers-reduced-motion: reduce) {
            .rv { opacity: 1; transform: none; transition: none; }
            .phone { animation: none !important; }
        }
    </style>
</head>
<body>

<div class="cursor-glow" id="cursorGlow"></div>

<section class="relative min-h-screen flex items-center justify-center overflow-hidden py-20 px-6">

    <div class="phone mockup-left">
        <div class="phone-screen">
            <div class="phone-notch"></div>
            <div class="phone-homebar"></div>
            <!-- Status bar -->
            <div class="phone-statusbar">
                <span>9:41</span>
                <div class="flex items-center gap-1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 8.5C1 4.91 3.75 2 7.5 2c2.5 0 4.65 1.37 5.72 3.38C14.28 3.37 16.43 2 18.93 2c3.75 0 6.5 2.91 6.5 6.5 0 4.84-6.24 11-8.93 13.5C13.75 19.5 7.5 13.34 7.5 8.5z" fill="currentColor"/></svg>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 5V2m12 3V2M5 9h14a1 1 0 011 1v9a1 1 0 01-1 1H5a1 1 0 01-1-1v-9a1 1 0 011-1z"/></svg>
                </div>
            </div>
            <div class="pt-8 px-3 pb-6 flex flex-col h-full">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-purple-200 to-indigo-200 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="3"/><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16"/></svg>
                        </div>
                        <span style="font-size:11px;font-weight:700;color:#111">Конкурсы</span>
                    </div>
                    <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center">
                        <svg class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.35-4.35"/></svg>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 bg-gray-100/80 rounded-lg px-2.5 py-2 mb-4">
                    <svg class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.35-4.35"/></svg>
                    <span style="font-size:9px;color:#aaa">Найти конкурс...</span>
                </div>
                <div class="flex gap-1.5 mb-3">
                    <div class="px-3 py-1 rounded-full text-[8px] font-bold bg-black text-white">Все</div>
                    <div class="px-3 py-1 rounded-full text-[8px] font-medium bg-gray-100 text-gray-500">Популярные</div>
                    <div class="px-3 py-1 rounded-full text-[8px] font-medium bg-gray-100 text-gray-500">Новые</div>
                </div>
                <div class="flex-1 space-y-2.5 overflow-hidden">

                    <div class="ui-card">
                        <div class="ui-card-gradient bg-gradient-to-br from-violet-200 via-purple-100 to-indigo-200">
                            <div class="absolute top-2 right-2"><span class="ui-badge bg-white/80 text-purple-700">🔥 148</span></div>
                            <div class="absolute bottom-2 left-2"><span class="ui-badge bg-black/70 text-white">2д 14ч</span></div>
                        </div>
                        <div class="p-2">
                            <div style="font-size:8px;font-weight:700;color:#111;margin-bottom:2px" class="truncate">Городские пейзажи</div>
                            <div style="font-size:7px;color:#888">Архитектура • 148 работ</div>
                        </div>
                    </div>
                    <div class="ui-card">
                        <div class="ui-card-gradient bg-gradient-to-br from-emerald-200 via-teal-100 to-cyan-200">
                            <div class="absolute top-2 right-2"><span class="ui-badge bg-white/80 text-emerald-700">🔥 89</span></div>
                            <div class="absolute bottom-2 left-2"><span class="ui-badge bg-black/70 text-white">5д 8ч</span></div>
                        </div>
                        <div class="p-2">
                            <div style="font-size:8px;font-weight:700;color:#111;margin-bottom:2px" class="truncate">Природа России</div>
                            <div style="font-size:7px;color:#888">Пейзаж • 89 работ</div>
                        </div>
                    </div>

                    <div class="ui-card">
                        <div class="ui-card-gradient bg-gradient-to-br from-rose-200 via-pink-100 to-fuchsia-200">
                            <div class="absolute top-2 right-2"><span class="ui-badge bg-white/80 text-rose-700">🔥 56</span></div>
                            <div class="absolute bottom-2 left-2"><span class="ui-badge bg-black/70 text-white">1д 3ч</span></div>
                        </div>
                        <div class="p-2">
                            <div style="font-size:8px;font-weight:700;color:#111;margin-bottom:2px" class="truncate">Портреты в свете</div>
                            <div style="font-size:7px;color:#888">Портрет • 56 работ</div>
                        </div>
                    </div>
                    <div class="ui-card opacity-60">
                        <div class="ui-card-gradient bg-gradient-to-br from-amber-200 via-orange-100 to-yellow-200"></div>
                        <div class="p-2">
                            <div style="font-size:8px;font-weight:700;color:#111" class="truncate">Макро мир</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="phone mockup-right">
        <div class="phone-screen">
            <div class="phone-notch"></div>
            <div class="phone-homebar"></div>
            <div class="phone-statusbar">
                <span>9:41</span>
                <div class="flex items-center gap-1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 8.5C1 4.91 3.75 2 7.5 2c2.5 0 4.65 1.37 5.72 3.38C14.28 3.37 16.43 2 18.93 2c3.75 0 6.5 2.91 6.5 6.5 0 4.84-6.24 11-8.93 13.5C13.75 19.5 7.5 13.34 7.5 8.5z" fill="currentColor"/></svg>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 5V2m12 3V2M5 9h14a1 1 0 011 1v9a1 1 0 01-1 1H5a1 1 0 01-1-1v-9a1 1 0 011-1z"/></svg>
                </div>
            </div>
            <!-- Screen content -->
            <div class="relative h-full">
                <div class="h-[58%] bg-gradient-to-br from-indigo-300 via-purple-200 to-pink-200 relative">

                    <div class="absolute top-8 left-3 right-3 flex justify-between items-center px-1">
                        <svg class="w-5 h-5 text-white/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                        <svg class="w-5 h-5 text-white/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12v8a2 2 0 002 2h12a2 2 0 002-2v-8M16 6l-4-4-4 4M12 2v13"/></svg>
                    </div>
                    <div class="absolute inset-4 flex items-center justify-center">
                        <svg class="w-14 h-14 text-white/40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            <circle cx="15" cy="9" r="1.5" fill="currentColor"/>
                        </svg>
                    </div>
                </div>

                <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-[20px] px-3.5 pt-3 pb-6">
                    <div class="w-8 h-1 bg-gray-200 rounded-full mx-auto mb-3"></div>

                    <div style="font-size:11px;font-weight:700;color:#111;margin-bottom:4px">Утренний туман над озером</div>

                    <div class="flex items-center gap-2 mb-3">
                        <div class="ui-avatar bg-gradient-to-br from-purple-300 to-indigo-400">А</div>
                        <div>
                            <div style="font-size:8px;font-weight:600;color:#111">Алексей К.</div>
                            <div style="font-size:7px;color:#888">2 часа назад</div>
                        </div>
                        <div class="ml-auto ui-badge bg-green-100 text-green-700">✓ Принято</div>
                    </div>

                    <div class="flex items-center justify-between mb-3">
                        <div class="ui-stars">
                            <svg viewBox="0 0 24 24" fill="#f59e0b"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <svg viewBox="0 0 24 24" fill="#f59e0b"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <svg viewBox="0 0 24 24" fill="#f59e0b"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <svg viewBox="0 0 24 24" fill="#f59e0b"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <svg viewBox="0 0 24 24" fill="#e5e5e5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        </div>
                        <div style="font-size:10px;font-weight:700;color:#f59e0b">4.2</div>
                    </div>

                    <div class="flex gap-2">
                        <div class="ui-btn">Участвовать</div>
                        <div class="ui-btn ui-btn-outline flex-1">Подробнее</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="floating-card hidden xl:block" style="top: 18%; right: 18%; animation-delay: -1.5s;">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-3 h-3 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M5 12l5 5L20 7"/></svg>
            </div>
            <div>
                <div style="font-size:9px;font-weight:700;color:#111">+247 оценок</div>
                <div style="font-size:7px;color:#888">за последние 24ч</div>
            </div>
        </div>
    </div>

    <div class="relative z-10 max-w-2xl text-center rv active">
        <div class="hero-badge mb-8">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Платформа фотоконкурсов</span>
        </div>

        <h1 class="text-4xl md:text-5xl lg:text-[3.8rem] font-extrabold leading-[1.08] mb-5 tracking-tight">
            Твой взгляд.<br>
            <span class="text-accent">В центре внимания.</span>
        </h1>

        <p class="text-base md:text-[1.05rem] text-text-muted mb-10 max-w-md mx-auto leading-relaxed">
            Участвуй в конкурсах, получай оценки от жюри и делись своим видением мира.
        </p>

        <div class="flex flex-wrap justify-center gap-3">
            <?php if (Yii::$app->user->isGuest): ?>
                <?= Html::a('Начать сейчас', ['/site/signup'], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Войти', ['/site/login'], ['class' => 'btn btn-outline']) ?>
            <?php else: ?>
                <?= Html::a('Все конкурсы', ['/contest/contests'], ['class' => 'btn btn-outline']) ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="py-20 px-6 bg-white border-y border-border">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-14 rv">
            <h2 class="text-3xl md:text-4xl font-bold mb-3 tracking-tight">Наши достижения</h2>
            <p class="text-text-muted text-base max-w-lg mx-auto">Цифры, которые говорят сами за себя</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php
            $statItems = [
                ['count' => $stats['users'], 'label' => 'Участников'],
                ['count' => $stats['submissions'], 'label' => 'Работ'],
                ['count' => $stats['nominations'], 'label' => 'Номинаций'],
                ['count' => $stats['contests'], 'label' => 'Конкурсов'],
            ];
            foreach ($statItems as $i => $stat): ?>
                <div class="card-pro text-center rv d<?= $i + 1 ?>">
                    <div class="text-3xl font-bold text-accent mb-1" data-count="<?= $stat['count'] ?>">0</div>
                    <div class="text-xs text-text-muted font-medium"><?= $stat['label'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-20 px-6">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-14 rv">
            <h2 class="text-3xl md:text-4xl font-bold mb-3 tracking-tight">Популярные конкурсы</h2>
            <p class="text-text-muted text-base max-w-lg mx-auto">Присоединяйся к активным фотоконкурсам</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($popularContests as $index => $konkurs): ?>
                <div class="card-pro rv d<?= min($index + 1, 3) ?>">
                    <div class="flex items-start justify-between mb-4">
                        <span class="badge-pro">🔥 <?= $konkurs['work_count'] ?? 0 ?> работ</span>
                    </div>
                    <h3 class="text-lg font-semibold text-text mb-2 line-clamp-1"><?= Html::encode($konkurs['title']) ?></h3>
                    <p class="text-sm text-text-muted mb-5 line-clamp-2"><?= Html::encode($konkurs['description']) ?></p>
                    <div class="pt-4 border-t border-border mt-auto">
                        <div class="flex items-center gap-2 text-xs text-text-muted mb-4 bg-accent-light px-3 py-2 rounded-lg">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>

                            <?= ruDate($konkurs['start_date']) ?> — <?= ruDate($konkurs['end_date']) ?>
                        </div>
                        <div class="flex gap-3">
                            <?= Html::a('Участвовать', ['/submission/submission', 'konkurs_id' => $konkurs['id']], ['class' => 'btn btn-primary flex-1 py-2.5 text-sm']) ?>
                            <?= Html::a('Номинации', ['/contest/nominations', 'konkurs_id' => $konkurs['id']], ['class' => 'btn btn-outline flex-1 py-2.5 text-sm']) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if (!empty($recommended)): ?>
    <section class="py-20 px-6 bg-white border-y border-border">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-14 rv">
                <h2 class="text-3xl md:text-4xl font-bold mb-3 tracking-tight">
                    <?= Yii::$app->user->isGuest ? 'Популярное' : 'Рекомендуем для вас' ?>
                </h2>
                <p class="text-text-muted text-base max-w-lg mx-auto">Лучшие работы наших участников</p>
            </div>
            <div class="works-grid">
                <?php foreach ($recommended as $index => $item): ?>
                    <a href="<?= Url::to(['/submission/submissions']) ?>#work-<?= $item->id ?>"
                       class="work-card rv d<?= min($index + 1, 4) ?>">
                        <div class="work-img">
                            <?php if ($item->image1): ?>
                                <img src="<?= Yii::getAlias('@web/' . $item->image1) ?>" alt="<?= Html::encode($item->title) ?>" loading="lazy" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-accent opacity-30">
                                    <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-4">
                            <h4 class="text-sm font-semibold text-text line-clamp-2 mb-1"><?= Html::encode($item->title) ?></h4>
                            <?php if ($item->user): ?>
                                <span class="text-xs text-text-muted">by <?= Html::encode($item->user->name . ' ' . $item->user->surname) ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if (Yii::$app->user->isGuest): ?>
    <section class="py-24 px-6 text-center">
        <div class="max-w-xl mx-auto rv">
            <h2 class="text-3xl md:text-4xl font-bold mb-4 tracking-tight">Готов показать свой талант?</h2>
            <p class="text-text-muted text-base mb-8 leading-relaxed">Регистрация займёт меньше минуты. Начни участвовать в конкурсах уже сегодня.</p>
            <?= Html::a('Создать аккаунт', ['/site/signup'], ['class' => 'btn btn-primary px-10 py-4 text-base']) ?>
        </div>
    </section>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const glow = document.getElementById('cursorGlow');
        let tx = 0, ty = 0, mx = 0, my = 0;
        document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
        (function animate() {
            tx += (mx - tx) * 0.1; ty += (my - ty) * 0.1;
            glow.style.transform = `translate(${tx - 300}px, ${ty - 300}px)`;
            requestAnimationFrame(animate);
        })();


        const ro = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('active'); ro.unobserve(e.target); } });
        }, { threshold: 0.15, rootMargin: '0px 0px -20px 0px' });
        document.querySelectorAll('.rv').forEach(el => ro.observe(el));

        const co = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    const el = e.target, t = parseInt(el.dataset.count) || 0;
                    let c = 0, s = Math.max(1, Math.ceil(t / 50));
                    (function tick() {
                        c += s;
                        if (c >= t) { el.textContent = t.toLocaleString('ru-RU'); }
                        else { el.textContent = c.toLocaleString('ru-RU'); requestAnimationFrame(tick); }
                    })();
                    co.unobserve(el);
                }
            });
        }, { threshold: 0.6 });
        document.querySelectorAll('[data-count]').forEach(el => co.observe(el));

        const phones = document.querySelectorAll('.phone');
        if (phones.length) {
            let isHovered = false;

            // Отслеживаем ховер на мокапах
            phones.forEach(p => {
                p.addEventListener('mouseenter', () => { isHovered = true; });
                p.addEventListener('mouseleave', () => { isHovered = false; });
            });

            window.addEventListener('scroll', () => {
                // Не применяем скролл-эффект, если пользователь навёл на мокап
                if (isHovered) return;

                const y = window.pageYOffset;
                phones.forEach((p, i) => {
                    const speed = 0.018 + i * 0.01;
                    const rotY = i === 0 ? 10 : -8;
                    const rotX = i === 0 ? -1.5 : 1.5;
                    p.style.transform = `translateY(calc(-50% + ${y * speed * -1}px)) perspective(1200px) rotateY(${rotY}deg) rotateX(${rotX}deg)`;
                });
            }, { passive: true });
        }
    });
</script>
</body>
</html>
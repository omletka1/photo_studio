<?php
/** @var yii\web\View $this */
/** @var app\models\Konkurs[] $activeContests */
/** @var app\models\Nomination[] $nominations */

use yii\helpers\Html;

$this->title = 'Правила участия';
?>

<!-- Убираем лишние обёртки — .main-container уже в main.php -->
<div class="rules-page">

    <!-- Hero Header -->
    <header class="rules-hero rv active">
        <div class="hero-content">
            <h1 class="hero-title">Правила участия в Photo Studio</h1>
            <p class="hero-subtitle">Масштабная онлайн-фотопремия для любителей и профессионалов. Честная оценка, открытое жюри, реальные призы.</p>
        </div>
    </header>

    <div class="rules-content">

        <!-- 1. О конкурсе -->
        <section class="rules-section rv">
            <h2 class="section-title">
                <span class="section-number">1</span>
                О конкурсе
            </h2>
            <p class="section-text">
                Photo Studio — это открытая платформа для фотографов любого уровня. Мы создали прозрачную систему оценки,
                где каждая работа получает комментарий профессионального жюри и оценку зрителей.
                Лучшие снимки попадают в галерею премии, а авторы получают сертификаты и ценные призы.
            </p>
        </section>


        <!-- 3. Основные правила -->
        <section class="rules-section rv">
            <h2 class="section-title">
                <span class="section-number">2</span>
                Основные правила
            </h2>
            <ul class="rules-list">
                <li class="rule-item"><span class="rule-bullet"></span>К участию допускаются фотографы старше 16 лет</li>
                <li class="rule-item"><span class="rule-bullet"></span>Максимум 5 работ в одной номинации</li>
                <li class="rule-item"><span class="rule-bullet"></span>Все фотографии должны быть сделаны участником лично</li>
                <li class="rule-item"><span class="rule-bullet"></span>Допускается минимальная обработка: цветокоррекция, кадрирование, удаление пыли</li>
                <li class="rule-item"><span class="rule-bullet"></span>Запрещено использование нейросетей для генерации изображений или значительной доработки</li>
                <li class="rule-item"><span class="rule-bullet"></span>Организаторы вправе использовать работы в некоммерческих промо-материалах премии</li>
                <li class="rule-item"><span class="rule-bullet"></span>Решения жюри окончательны и не подлежат обжалованию</li>
            </ul>
        </section>

        <!-- 4. Технические требования -->
        <section class="rules-section rv">
            <h2 class="section-title">
                <span class="section-number">3</span>
                Технические требования
            </h2>
            <div class="specs-grid">
                <div class="spec-item">
                    <strong class="spec-label">Формат файла</strong>
                    <span class="spec-value">JPG, PNG (макс. 15 МБ на файл)</span>
                </div>
                <div class="spec-item">
                    <strong class="spec-label">Разрешение</strong>
                    <span class="spec-value">Минимум 2000 px по длинной стороне</span>
                </div>
                <div class="spec-item">
                    <strong class="spec-label">Цветовой профиль</strong>
                    <span class="spec-value">sRGB</span>
                </div>
                <div class="spec-item">
                    <strong class="spec-label">EXIF-данные</strong>
                    <span class="spec-value">Должны быть сохранены (проверка жюри)</span>
                </div>
            </div>
        </section>

        <!-- 5. Номинации -->
        <section class="rules-section rv">
            <h2 class="section-title">
                <span class="section-number">4</span>
                Номинации
            </h2>
            <?php if (!empty($nominations)): ?>
                <div id="nominations-grid" class="nominations-grid">
                    <?php foreach ($nominations as $i => $nom): ?>
                        <span class="nomination-tag <?= $i >= 10 ? 'hidden' : '' ?>"><?= Html::encode($nom->title) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php if (count($nominations) > 10): ?>
                    <button id="toggleNomBtn" type="button" class="btn-toggle">
                        <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                        Показать ещё <?= count($nominations) - 10 ?>
                    </button>
                <?php endif; ?>
            <?php else: ?>
                <p class="empty-nominations">Номинации будут объявлены при запуске нового конкурса.</p>
            <?php endif; ?>
        </section>

        <!-- 6. Жюри и оценка -->
        <section class="rules-section rv">
            <h2 class="section-title">
                <span class="section-number">5</span>
                Жюри и оценка
            </h2>
            <p class="section-text">
                Каждая работа оценивается профессиональным жюри по 5-балльной шкале.
                Итоговый балл складывается из средних оценок всех членов жюри.
                Дополнительно учитываются зрительские голоса.
            </p>
            <div class="score-legend">
                <div class="legend-item">
                    <span class="legend-dot high"></span>
                    <span>4.5–5.0 — Отлично</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot mid"></span>
                    <span>3.0–4.4 — Хорошо</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot low"></span>
                    <span>1.0–2.9 — Требует доработки</span>
                </div>
            </div>
        </section>

        <!-- CTA Actions -->
        <div class="rules-cta rv">
            <?= Html::a('
                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Подать заявку
            ', ['/submission/submission'], ['class' => 'btn-primary', 'encode' => false]) ?>
            <?= Html::a('
                <svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Все конкурсы
            ', ['/contest/contests'], ['class' => 'btn-secondary', 'encode' => false]) ?>
        </div>
    </div>
</div>

<style>
    :root { --ease: cubic-bezier(0.16, 1, 0.3, 1); }

    .rules-hero {
        background: linear-gradient(135deg, #8b77b3 0%, #6b5a91 100%);
        border-radius: 16px;
        padding: 40px 24px;
        margin-bottom: 32px;
        text-align: center;
        color: #fff;
    }
    .hero-title {
        font-size: clamp(1.8rem, 3vw, 2.3rem);
        font-weight: 700;
        margin: 0 0 12px 0;
        letter-spacing: -0.02em;
    }
    .hero-subtitle {
        font-size: 1rem;
        opacity: 0.95;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .rules-content { display: flex; flex-direction: column; gap: 32px; }

    .rules-section {
        background: #fff;
        border: 1px solid #e5e3eb;
        border-radius: 16px;
        padding: 24px;
        transition: box-shadow 0.3s var(--ease), border-color 0.2s ease;
    }
    .rules-section:hover {
        box-shadow: 0 12px 32px rgba(0,0,0,0.06);
        border-color: #d5d0e3;
    }

    .section-title {
        display: flex; align-items: center; gap: 12px;
        font-size: 1.25rem; font-weight: 700; color: #111118;
        margin: 0 0 16px 0;
    }
    .section-number {
        width: 32px; height: 32px; border-radius: 50%;
        background: #f0eaf5; color: #8b77b3;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; font-weight: 700; flex-shrink: 0;
    }

    .section-text {
        color: #6b6b80; line-height: 1.7; margin: 0; font-size: 0.95rem;
    }

    .contests-grid {
        display: grid; grid-template-columns: 1fr; gap: 12px;
    }
    @media (min-width: 768px) { .contests-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .contests-grid { grid-template-columns: repeat(3, 1fr); } }

    .contest-card {
        background: #fafaf8; border: 1px solid #e5e3eb; border-radius: 12px;
        padding: 20px; text-align: center; transition: all 0.25s var(--ease);
    }
    .contest-card:hover {
        border-color: #d5d0e3; transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.04);
    }
    .contest-label {
        display: block; font-size: 0.75rem; font-weight: 700;
        color: #8b77b3; text-transform: uppercase; letter-spacing: 0.08em;
        margin-bottom: 8px;
    }
    .contest-name {
        font-size: 1.05rem; font-weight: 600; color: #111118;
        margin: 0 0 8px 0;
    }
    .contest-dates {
        display: flex; align-items: center; justify-content: center; gap: 6px;
        font-size: 0.85rem; color: #6b6b80; margin-bottom: 12px;
    }
    .date-icon { width: 14px; height: 14px; color: #8b77b3; flex-shrink: 0; }
    .contest-status {
        display: inline-block; padding: 4px 12px; border-radius: 999px;
        background: #ecfdf5; color: #059669; font-size: 0.75rem; font-weight: 600;
    }

    .empty-contests {
        text-align: center; padding: 32px; color: #6b6b80;
        background: #fafaf8; border: 1px dashed #e5e3eb; border-radius: 12px;
    }
    .empty-icon { width: 48px; height: 48px; color: #d5d0e3; margin: 0 auto 12px; display: block; }

    .rules-list { display: flex; flex-direction: column; gap: 12px; margin: 0; padding: 0; list-style: none; }
    .rule-item {
        display: flex; align-items: flex-start; gap: 10px;
        color: #6b6b80; font-size: 0.95rem; line-height: 1.6;
    }
    .rule-bullet {
        width: 6px; height: 6px; border-radius: 50%;
        background: #8b77b3; margin-top: 8px; flex-shrink: 0;
    }

    .specs-grid {
        display: grid; grid-template-columns: 1fr; gap: 12px;
    }
    @media (min-width: 640px) { .specs-grid { grid-template-columns: repeat(2, 1fr); } }
    .spec-item {
        background: #fafaf8; border: 1px solid #e5e3eb; border-radius: 10px;
        padding: 16px;
    }
    .spec-label {
        display: block; font-weight: 600; color: #111118;
        margin-bottom: 4px; font-size: 0.9rem;
    }
    .spec-value { color: #6b6b80; font-size: 0.9rem; }

    .nominations-grid {
        display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px;
    }
    .nomination-tag {
        padding: 6px 14px; border-radius: 999px;
        background: #f3f4f6; color: #111118;
        font-size: 0.85rem; font-weight: 500;
        transition: all 0.2s var(--ease);
    }
    .nomination-tag:hover {
        background: #f0eaf5; color: #8b77b3;
    }
    .nomination-tag.hidden { display: none; }

    .btn-toggle {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px; background: #8b77b3; color: #fff;
        border: none; border-radius: 10px; font-size: 0.9rem; font-weight: 500;
        cursor: pointer; transition: all 0.25s var(--ease);
    }
    .btn-toggle:hover { background: #75639c; transform: translateY(-1px); }
    .btn-icon { width: 14px; height: 14px; }

    .empty-nominations {
        color: #6b6b80; background: #fafaf8; border: 1px dashed #e5e3eb;
        border-radius: 10px; padding: 16px; font-size: 0.95rem;
    }

    .score-legend {
        display: flex; flex-wrap: wrap; gap: 16px; margin-top: 12px;
    }
    .legend-item {
        display: flex; align-items: center; gap: 8px;
        font-size: 0.9rem; color: #111118;
    }
    .legend-dot {
        width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0;
    }
    .legend-dot.high { background: #10b981; }
    .legend-dot.mid { background: #f59e0b; }
    .legend-dot.low { background: #ef4444; }

    .rules-cta {
        display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;
        padding-top: 24px; border-top: 1px solid #e5e3eb;
    }
    .btn-primary, .btn-secondary {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 12px 24px; border-radius: 12px; font-weight: 600; font-size: 0.95rem;
        text-decoration: none; transition: all 0.25s var(--ease); cursor: pointer; border: none;
    }
    .btn-primary { background: #111118; color: #fff; }
    .btn-primary:hover { background: #222; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
    .btn-secondary { background: #fff; color: #111118; border: 1.5px solid #e5e3eb; }
    .btn-secondary:hover { border-color: #8b77b3; background: #f0eaf5; color: #8b77b3; }

    .rv { opacity: 0; transform: translateY(16px); transition: opacity 0.7s var(--ease), transform 0.7s var(--ease); }
    .rv.active { opacity: 1; transform: translateY(0); }

    @media (max-width: 640px) {
        .rules-hero { padding: 32px 20px; }
        .hero-title { font-size: 1.5rem; }
        .rules-section { padding: 20px; }
        .rules-cta { flex-direction: column; }
        .btn-primary, .btn-secondary { width: 100%; }
    }

    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: #f7f6f9; }
    ::-webkit-scrollbar-thumb { background: #d5d0e0; border-radius: 3px; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Scroll reveal
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) { entry.target.classList.add('active'); observer.unobserve(entry.target); }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -30px 0px' });
        document.querySelectorAll('.rv').forEach(el => observer.observe(el));

        // Toggle nominations
        const toggleBtn = document.getElementById('toggleNomBtn');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const items = document.querySelectorAll('.nomination-tag');
                const hidden = Array.from(items).filter(el => el.classList.contains('hidden'));

                if (hidden.length > 0) {
                    hidden.forEach(el => el.classList.remove('hidden'));
                    this.innerHTML = '<svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 15l-6-6-6 6"/></svg>Свернуть';
                } else {
                    items.forEach((el, i) => { if (i >= 10) el.classList.add('hidden'); });
                    this.innerHTML = '<svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>Показать ещё ' + (items.length - 10);
                }
            });
        }
    });
</script>
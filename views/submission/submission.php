<?php
use yii\helpers\Html;
use app\models\Konkurs;

$this->title = 'Участие в конкурсе';

$items = Konkurs::find()
    ->where(['status' => 'открыт'])
    ->select(['title'])
    ->indexBy('id')
    ->column();
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="scroll-smooth">
    <head>
        <?php $this->registerCsrfMetaTags() ?>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'sys-bg': '#f7f6f9',
                            'sys-surface': '#ffffff',
                            'sys-border': '#e5e3eb',
                            'sys-text': '#111118',
                            'sys-text-muted': '#6b6b80',
                            'sys-accent': '#8b77b3',
                            'sys-accent-hover': '#75639c',
                            'sys-accent-light': '#f0eaf5',
                        },
                        fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    }
                }
            }
        </script>
        <style>
            :root { --ease: cubic-bezier(0.16, 1, 0.3, 1); }
            * { box-sizing: border-box; }
            body {
                font-family: 'Inter', system-ui, sans-serif;
                background: #f7f6f9;
                color: #111118;
                -webkit-font-smoothing: antialiased;
                margin: 0;
                line-height: 1.6;
            }

            .form-container {
                max-width: 720px;
                margin: 48px auto;
                padding: 0 20px;
            }
            .form-card {
                background: #fff;
                border: 1px solid #e5e3eb;
                border-radius: 16px;
                padding: 32px;
                transition: box-shadow 0.3s var(--ease), border-color 0.2s ease;
            }
            .form-card:hover {
                box-shadow: 0 12px 32px rgba(0,0,0,0.06);
                border-color: #d5d0e3;
            }
            .form-title {
                font-size: 1.75rem;
                font-weight: 700;
                color: #111118;
                margin: 0 0 8px 0;
                letter-spacing: -0.02em;
                text-align: center;
            }
            .form-subtitle {
                text-align: center;
                color: #6b6b80;
                font-size: 0.95rem;
                margin: 0 0 24px 0;
            }
            .form-divider {
                height: 1px;
                background: #e5e3eb;
                margin: 24px 0 32px;
                border: none;
            }

            .form-group { margin-bottom: 24px; }
            .form-label {
                display: block;
                font-weight: 600;
                font-size: 0.9rem;
                color: #111118; /* ← нейтральный чёрный, без акцентов */
                margin-bottom: 8px;
            }
            .form-label-required::after {
                content: '*';
                color: #ef4444; /* красный только для обязательных полей */
                margin-left: 4px;
                font-weight: 400;
            }

            .form-input, .form-textarea, .form-select {
                width: 100%;
                padding: 12px 14px;
                font-size: 0.95rem;
                font-family: inherit;
                color: #111118;
                background: #fff;
                border: 1.5px solid #e5e3eb;
                border-radius: 10px;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }
            .form-input:focus, .form-textarea:focus, .form-select:focus {
                outline: none;
                border-color: #8b77b3; /* акцент только при фокусе */
                box-shadow: 0 0 0 3px rgba(139, 119, 179, 0.15);
            }
            .form-textarea {
                min-height: 120px;
                resize: vertical;
                line-height: 1.6;
            }
            .form-select {
                appearance: none;
                background-image: url("image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b6b80' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 12px center;
                padding-right: 36px;
                cursor: pointer;
            }

            /* File upload */
            .file-dropzone {
                border: 2px dashed #e5e3eb;
                border-radius: 12px;
                padding: 28px 20px;
                text-align: center;
                background: #fafaf8;
                transition: all 0.2s var(--ease);
                cursor: pointer;
            }
            .file-dropzone:hover, .file-dropzone.dragover {
                border-color: #8b77b3;
                background: #f0eaf5;
            }
            .file-dropzone-icon {
                width: 40px;
                height: 40px;
                color: #6b6b80;
                margin: 0 auto 12px;
                display: block;
            }
            .file-dropzone-text {
                font-weight: 500;
                color: #111118;
                margin-bottom: 4px;
            }
            .file-dropzone-hint {
                font-size: 0.85rem;
                color: #6b6b80;
            }
            .file-dropzone input[type="file"] { display: none; }

            .file-info {
                margin-top: 12px;
                font-size: 0.85rem;
                color: #6b6b80;
            }
            .file-info.warning { color: #ef4444; }

            .preview-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
                gap: 12px;
                margin-top: 20px;
            }
            .preview-item {
                position: relative;
                aspect-ratio: 1;
                border-radius: 10px;
                overflow: hidden;
                background: #f0eaf5;
                cursor: pointer;
            }
            .preview-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s var(--ease);
            }
            .preview-item:hover img { transform: scale(1.05); }
            .preview-remove {
                position: absolute;
                top: 6px;
                right: 6px;
                width: 22px;
                height: 22px;
                background: #ef4444;
                color: #fff;
                border: none;
                border-radius: 50%;
                font-size: 14px;
                line-height: 22px;
                cursor: pointer;
                transition: transform 0.2s ease;
            }
            .preview-remove:hover { transform: scale(1.1); }

            /* Submit button */
            .submit-btn {
                width: 100%;
                padding: 14px 24px;
                background: #111118;
                color: #fff;
                border: none;
                border-radius: 12px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.25s var(--ease);
                margin-top: 8px;
            }
            .submit-btn:hover {
                background: #222;
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            }
            .submit-btn:disabled {
                opacity: 0.6;
                cursor: not-allowed;
                transform: none;
            }

            /* Lightbox */
            .lightbox {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.92);
                display: none;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                padding: 20px;
            }
            .lightbox.active { display: flex; }
            .lightbox img {
                max-width: 100%;
                max-height: 90vh;
                border-radius: 8px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            }
            .lightbox-close {
                position: absolute;
                top: 20px;
                right: 24px;
                color: #fff;
                font-size: 28px;
                cursor: pointer;
                transition: color 0.2s ease;
            }
            .lightbox-close:hover { color: #8b77b3; }

            @media (max-width: 640px) {
                .form-card { padding: 24px 20px; }
                .form-title { font-size: 1.4rem; }
                .preview-grid { grid-template-columns: repeat(3, 1fr); }
            }

            ::-webkit-scrollbar { width: 5px; }
            ::-webkit-scrollbar-track { background: #f7f6f9; }
            ::-webkit-scrollbar-thumb { background: #d5d0e0; border-radius: 3px; }
        </style>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="form-container">
        <div class="form-card">
            <h1 class="form-title">Участие в конкурсе</h1>
            <p class="form-subtitle">Заполните форму и отправьте свою работу на рассмотрение</p>
            <hr class="form-divider">

            <form method="post" enctype="multipart/form-data" id="submission-form">
                <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">

                <div class="form-group">
                    <label for="submission-title" class="form-label form-label-required">Название работы</label>
                    <textarea name="Submission[title]" id="submission-title" class="form-textarea"
                              placeholder="Введите название работы" required><?= Html::encode($model->title ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="submission-description" class="form-label form-label-required">Описание</label>
                    <textarea name="Submission[description]" id="submission-description" class="form-textarea"
                              placeholder="Расскажите о своей работе" required><?= Html::encode($model->description ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="submission-konkurs" class="form-label form-label-required">Конкурс</label>
                    <select name="Submission[konkurs_id]" id="submission-konkurs" class="form-select" required>
                        <option value="">Выберите конкурс</option>
                        <?php foreach ($items as $id => $title): ?>
                            <option value="<?= $id ?>" <?= (isset($model->konkurs_id) && $model->konkurs_id == $id) ? 'selected' : '' ?>>
                                <?= Html::encode($title) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label form-label-required">Фотографии</label>
                    <label class="file-dropzone" id="dropZone">
                        <svg class="file-dropzone-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            <path d="M12 11v6M9 14l3-3 3 3"/>
                        </svg>
                        <div class="file-dropzone-text">Перетащите фото или кликните</div>
                        <div class="file-dropzone-hint">До 5 файлов, форматы: JPG, PNG, WEBP</div>
                        <input type="file" name="Submission[imageFile][]" id="fileInput" multiple accept="image/*">
                    </label>
                    <div class="file-info"><span id="fileCount">0</span> из 5 файлов выбрано</div>
                    <div class="file-info warning" id="fileWarning" style="display:none">Максимум 5 файлов</div>

                    <div class="preview-grid" id="previewContainer">
                        <?php for ($i = 1; $i <= 5; $i++):
                            $attr = 'image' . $i;
                            if (!empty($model->$attr)): ?>
                                <div class="preview-item">
                                    <img src="/<?= Html::encode($model->$attr) ?>" alt="" loading="lazy">
                                </div>
                            <?php endif;
                        endfor; ?>
                    </div>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">Отправить заявку</button>
            </form>
        </div>
    </div>

    <div class="lightbox" id="lightbox">
        <span class="lightbox-close">&times;</span>
        <img src="" alt="">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fileInput = document.getElementById('fileInput');
            const dropZone = document.getElementById('dropZone');
            const previewContainer = document.getElementById('previewContainer');
            const fileCount = document.getElementById('fileCount');
            const fileWarning = document.getElementById('fileWarning');
            const form = document.getElementById('submission-form');
            const submitBtn = document.getElementById('submitBtn');
            const lightbox = document.getElementById('lightbox');

            let files = [];
            const MAX_FILES = 5;

            function updateFilesInput() {
                const dt = new DataTransfer();
                files.forEach(f => dt.items.add(f));
                fileInput.files = dt.files;
            }

            function renderPreviews() {
                const existing = previewContainer.querySelectorAll('.preview-item:not([data-new])');
                previewContainer.innerHTML = '';
                existing.forEach(el => previewContainer.appendChild(el));

                files.forEach((file, idx) => {
                    const reader = new FileReader();
                    reader.onload = e => {
                        const wrap = document.createElement('div');
                        wrap.className = 'preview-item';
                        wrap.dataset.new = '1';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = file.name;
                        img.onclick = () => {
                            lightbox.querySelector('img').src = img.src;
                            lightbox.classList.add('active');
                        };

                        const btn = document.createElement('button');
                        btn.className = 'preview-remove';
                        btn.innerHTML = '&times;';
                        btn.onclick = ev => {
                            ev.stopPropagation();
                            files.splice(idx, 1);
                            updateFilesInput();
                            renderPreviews();
                            updateInfo();
                        };

                        wrap.append(img, btn);
                        previewContainer.appendChild(wrap);
                    };
                    reader.readAsDataURL(file);
                });
            }

            function updateInfo() {
                fileCount.textContent = files.length;
                fileWarning.style.display = files.length >= MAX_FILES ? 'block' : 'none';
            }

            function handleFiles(newFiles) {
                for (let f of newFiles) {
                    if (!f.type.startsWith('image/')) continue;
                    const dup = files.some(x => x.name === f.name && x.size === f.size);
                    if (dup) continue;
                    if (files.length < MAX_FILES) files.push(f);
                }
                updateFilesInput();
                renderPreviews();
                updateInfo();
            }

            fileInput?.addEventListener('change', e => handleFiles(e.target.files));

            if (dropZone) {
                ['dragenter','dragover'].forEach(ev =>
                    dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.add('dragover'); })
                );
                ['dragleave','drop'].forEach(ev =>
                    dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.remove('dragover'); })
                );
                dropZone.addEventListener('drop', e => handleFiles(e.dataTransfer.files));
                dropZone.addEventListener('click', () => fileInput?.click());
            }

            lightbox?.addEventListener('click', e => {
                if (e.target === lightbox || e.target.classList.contains('lightbox-close')) {
                    lightbox.classList.remove('active');
                }
            });

            form?.addEventListener('submit', () => {
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Отправка...';
                }
            });
        });
    </script>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
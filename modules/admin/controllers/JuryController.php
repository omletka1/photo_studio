<?php
namespace app\modules\admin\controllers;

use app\models\JuryComment;
use app\models\JuryRating;
use app\models\KonkursNominations;
use app\models\Nomination;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use app\models\Konkurs;
use app\models\KonkursJury;
use app\models\Submission;

class JuryController extends Controller
{
    // 🔐 Проверка доступа ко всем действиям
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException('Необходимо войти в систему');
        }

        $role = Yii::$app->user->identity->role ?? 0;
        // Доступ для админа (1) и жюри (2)
        if (!in_array($role, [1, 2])) {
            throw new ForbiddenHttpException('Доступ запрещён');
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $userId = Yii::$app->user->id;

        $konkursIds = KonkursJury::find()
            ->where(['user_id' => $userId])
            ->select('konkurs_id')
            ->column();

        $konkurs = Konkurs::find()
            ->where(['id' => $konkursIds])
            ->all();

        return $this->render('index', [
            'konkurs' => $konkurs
        ]);
    }
    /**
     * Оценка работы (форма + сохранение)
     * @param int $submission_id ID работы
     */
    public function actionRate($submission_id)
    {
        // 🔥 Находим работу
        $submission = \app\models\Submission::findOne($submission_id);

        if (!$submission) {
            throw new \yii\web\NotFoundHttpException('Работа не найдена');
        }

        // 🔥 Проверяем доступ (опционально)
        $userId = Yii::$app->user->id;
        $isAdmin = (Yii::$app->user->identity->role ?? 0) == 1;

        if (!$isAdmin) {
            $hasAccess = \app\models\KonkursJury::find()
                ->where([
                    'konkurs_id' => $submission->konkurs_id,
                    'user_id' => $userId
                ])->exists();

            if (!$hasAccess) {
                throw new \yii\web\ForbiddenHttpException('Нет доступа к оценке этой работы');
            }
        }

        // 🔥 Получаем номинации конкурса
        $nominations = \app\models\Nomination::find()
            ->innerJoin('konkurs_nominations', 'konkurs_nominations.nomination_id = nominations.id')  // ← nominations.id ✅
            ->where(['konkurs_nominations.konkurs_id' => $submission->konkurs_id])
            ->all();

        // 🔥 Обработка сохранения оценок
        if (Yii::$app->request->isPost) {
            $scores = Yii::$app->request->post('score', []);

            // Удаляем старые оценки этого пользователя
            \app\models\JuryRating::deleteAll([
                'submission_id' => $submission_id,
                'user_id' => $userId
            ]);

// 🔥 Внутри цикла foreach ($scores as $nomId => $score):
            foreach ($scores as $nomId => $score) {
                if ($score !== '' && $score !== null) {
                    $rating = new \app\models\JuryRating();
                    $rating->submission_id = $submission_id;
                    $rating->nomination_id = $nomId;
                    $rating->user_id = $userId;
                    $rating->score = (int)$score;

                    // 🔥 Сохраняем комментарий к номинации
                    $comments = Yii::$app->request->post('comment', []);
                    $rating->comment = trim($comments[$nomId] ?? '');

                    // 🔥 Логирование для отладки
                    if ($rating->save()) {
                        Yii::info("Rating saved: nom={$nomId}, score={$score}, comment={$rating->comment}", __METHOD__);
                    } else {
                        Yii::error("Rating save failed: " . print_r($rating->errors, true), __METHOD__);
                    }
                }
            }

            Yii::$app->session->setFlash('success', '✅ Оценки сохранены');
            return $this->redirect(['/admin/konkurs/view', 'id' => $submission->konkurs_id]);
        }

        // 🔥 Рендерим представление — ВАЖНО: передаём 'submission', а не 'model'!
        return $this->render('rate', [
            'submission' => $submission,  // ← Имя должно совпадать с тем, что в view!
            'nominations' => $nominations,
        ]);
    }
    public function actionSubmissions($konkurs_id)
    {
        $userId = Yii::$app->user->id;

        $allowed = KonkursJury::find()
            ->where([
                'konkurs_id' => $konkurs_id,
                'user_id' => $userId
            ])->exists();

        if (!$allowed) {
            throw new ForbiddenHttpException('Нет доступа к этому конкурсу');
        }

        $submissions = Submission::find()
            ->where(['konkurs_id' => $konkurs_id])
            ->with(['user'])
            ->all();

        $avgScores = [];
        foreach ($submissions as $s) {
            $avgScores[$s->id] = JuryRating::find()
                ->where(['submission_id' => $s->id])
                ->average('score');
        }

        return $this->render('submissions', [
            'submissions' => $submissions,
            'konkurs_id' => $konkurs_id,
            'avgScores' => $avgScores,
        ]);
    }

    /**
     * 🔥 Удаление комментария (AJAX)
     * - Админ (role=1): удаляет любые
     * - Жюри (role=2): только свои
     */
    public function actionDeleteComment($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $comment = JuryComment::findOne($id);

        if (!$comment) {
            return ['success' => false, 'error' => 'Комментарий не найден'];
        }

        $identity = Yii::$app->user->identity;
        $role = $identity->role ?? 0;
        $currentUserId = Yii::$app->user->id;

        // Проверка прав
        $canDelete = ($role == 1) || ($role == 2 && $comment->user_id == $currentUserId);

        if (!$canDelete) {
            return ['success' => false, 'error' => 'Нет прав на удаление'];
        }

        // Удаляем все ответы на этот комментарий (опционально, каскад)
        // JuryComment::deleteAll(['parent_id' => $id]);

        if ($comment->delete()) {
            return ['success' => true];
        }

        return ['success' => false, 'error' => 'Ошибка при удалении'];
    }

    /**
     * 🔥 Добавление комментария (AJAX)
     */
    public function actionAddComment()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!Yii::$app->request->isPost || Yii::$app->user->isGuest) {
            Yii::warning('AddComment: invalid request or guest', __METHOD__);
            return ['success' => false, 'error' => 'Некорректный запрос'];
        }

        $comment = new JuryComment();
        $comment->submission_id = Yii::$app->request->post('submission_id');
        $comment->user_id = Yii::$app->user->id;
        $comment->text = trim(Yii::$app->request->post('text', ''));
        $comment->parent_id = Yii::$app->request->post('parent_id') ?: null;  // 🔥 Явно приводим к null

        // 🔥 Дебаг-лог
        Yii::info('AddComment debug: ' . print_r([
                'submission_id' => $comment->submission_id,
                'parent_id' => $comment->parent_id,
                'text_length' => strlen($comment->text),
                'rules' => $comment->rules(),
            ], true), __METHOD__);

        if ($comment->text === '') {
            return ['success' => false, 'errors' => ['text' => ['Текст не может быть пустым']]];
        }

        if ($comment->save()) {
            return [
                'success' => true,
                'parent_id' => $comment->parent_id,
                'html' => $this->renderPartial('_comment', [
                    'comment' => $comment,
                    'submissionId' => $comment->submission_id
                ])
            ];
        }

        // 🔥 Логируем ошибки валидации
        $errors = $comment->getFirstErrors();
        Yii::error('AddComment validation failed: ' . print_r($errors, true), __METHOD__);

        return [
            'success' => false,
            'errors' => $errors,
            'error' => 'Ошибка валидации: ' . json_encode($errors)  // 🔥 Передаём текст клиенту
        ];
    }
    /**
     * Детальный просмотр работы для жюри
     */
    public function actionView($id)
    {
        $work = Submission::findOne($id);
        if (!$work) {
            throw new NotFoundHttpException('Работа не найдена');
        }

        // 🔥 Проверка доступа: жюри может смотреть только свои конкурсы
        $userId = Yii::$app->user->id;
        $isAdmin = (Yii::$app->user->identity->role ?? 0) == 1;

        if (!$isAdmin) {
            $hasAccess = KonkursJury::find()
                ->where([
                    'konkurs_id' => $work->konkurs_id,
                    'user_id' => $userId
                ])->exists();

            if (!$hasAccess) {
                throw new ForbiddenHttpException('Нет доступа к этой работе');
            }
        }

        // 🔥 Подгружаем связанные данные
        $work = Submission::find()
            ->with(['user', 'konkurs', 'nomination'])
            ->where(['id' => $id])
            ->one();

        // 🔥 Статистика
        $voteCount = \app\models\Vote::find()->where(['submission_id' => $id])->count();

        $juryRatings = \app\models\JuryRating::find()
            ->where(['submission_id' => $id])
            ->with(['user', 'nomination'])
            ->all();

        $avgScore = $juryRatings ? round(array_sum(array_column($juryRatings, 'score')) / count($juryRatings), 2) : 0;

        // 🔥 Комментарии (корневые + ответы)
        $comments = \app\models\JuryComment::find()
            ->where(['submission_id' => $id, 'parent_id' => null])
            ->with(['user', 'replies.user'])
            ->orderBy(['created_at' => SORT_ASC])
            ->all();

        // 🔥 Изображения
        $images = [];
        for ($i = 1; $i <= 5; $i++) {
            $field = 'image' . $i;
            if (!empty($work->$field)) {
                $images[] = Yii::getAlias('@web/' . ltrim($work->$field, '/'));
            }
        }

        return $this->render('view', [
            'work' => $work,
            'images' => $images,
            'voteCount' => $voteCount,
            'juryRatings' => $juryRatings,
            'avgScore' => $avgScore,
            'comments' => $comments,
        ]);
    }
}
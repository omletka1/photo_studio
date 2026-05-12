<?php

namespace app\controllers;

use app\models\Application;
use app\models\Category;
use app\models\Comments;
use app\models\ContactRequest;
use app\models\Konkurs;
use app\models\News;
use app\models\Nomination;
use app\models\Organizers;
use app\models\SignupForm;
use app\models\Submission;
use app\models\User;
use app\models\VerifyCodeForm;
use app\models\Vote;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'vote'],
                'rules' => [
                    [
                        'actions' => ['logout', 'vote'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['vote'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'vote' => ['post'],
                ],
            ],
        ];
    }

    public function init()
    {
        parent::init();

        // 🔥 Автоматически закрываем просроченные конкурсы (только для демо!)
        // В реальном проекте это лучше вынести в cron-задачу
        try {
            \Yii::$app->db->createCommand()
                ->update('konkurs', ['status' => 'закрыт'], 'status = "открыт" AND end_date < CURDATE()')
                ->execute();
        } catch (\Exception $e) {
            // Если БД недоступна или ошибка, просто игнорируем, чтобы сайт не падал
        }
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // 🔥 1. Активные конкурсы (открытые, не просроченные)
        $activeContests = Konkurs::find()
            ->where(['status' => 'открыт'])
            ->andWhere(['>=', 'end_date', date('Y-m-d')])
            ->orderBy(['start_date' => SORT_ASC])
            ->limit(3)
            ->all();

// 🔥 Популярные конкурсы (по количеству поданных работ)
// 🔥 Популярные конкурсы (по количеству поданных работ)
        $popularContests = (new \yii\db\Query())
            ->select([
                'konkurs.*',
                'COUNT(submission.id) as work_count'
            ])
            ->from(['konkurs' => 'konkurs'])
            ->leftJoin('submission', 'submission.konkurs_id = konkurs.id')
            ->where(['konkurs.status' => 'открыт'])
            ->groupBy('konkurs.id')
            ->orderBy(['work_count' => SORT_DESC, 'konkurs.start_date' => SORT_DESC])
            ->limit(3)
            ->all(); // ← возвращает массив массивов, НЕ моделей


        // 🔥 3. Последние работы (для галереи)
        $recentWorks = Submission::find()
            ->where(['not', ['image1' => '']])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(8)
            ->all();

        // 🔥 4. Статистика
        $stats = [
            'users' => \app\models\User::find()->count(),
            'submissions' => \app\models\Submission::find()->count(),
            'nominations' => \app\models\Nomination::find()->count(),
            'contests' => \app\models\Konkurs::find()->where(['status' => 'открыт'])->count(), // ← вернули 'contests'
        ];

        // 🔥 5. Умные рекомендации
        $recommended = $this->getRecommendedSubmissions(8);

        return $this->render('index', [
            'activeContests' => $activeContests,
            'popularContests' => $popularContests,
            'recentWorks' => $recentWorks,
            'stats' => $stats,
            'recommended' => $recommended,
        ]);
    }

    /**
     * 🔥 Умная система рекомендаций
     * - Для авторизованных: на основе лайков + популярных в тех же номинациях
     * - Для гостей: топ по голосованиям + свежие работы
     */
    /**
     * 🔥 Умная система рекомендаций (исправленная версия)
     */
    private function getRecommendedSubmissions($limit = 8)
    {
        $userId = Yii::$app->user->id;

        if (!Yii::$app->user->isGuest) {
            // Получаем ID работ, которые пользователь лайкнул
            $likedSubmissionIds = Vote::find()
                ->where(['user_id' => $userId])
                ->select('submission_id')
                ->column();

            if (!empty($likedSubmissionIds)) {
                // Получаем номинации лайкнутых работ
                $likedNominationIds = Submission::find()
                    ->where(['id' => $likedSubmissionIds])
                    ->andWhere(['not', ['nomination_id' => null]])
                    ->select('nomination_id')
                    ->column();

                if (!empty($likedNominationIds)) {
                    // Рекомендации: работы в тех же номинациях, которые пользователь ещё не лайкал
                    return Submission::find()
                        ->where(['nomination_id' => $likedNominationIds])
                        ->andWhere(['not in', 'id', $likedSubmissionIds])
                        ->andWhere(['not', ['image1' => '']])
                        ->orderBy(['submission.created_at' => SORT_DESC])
                        ->limit($limit)
                        ->all();
                }
            }
        }

        // 🔥 Fallback: популярные работы по количеству голосов
        // Используем подзапрос с явным алиасом и правильной сортировкой
        $subQuery = (new \yii\db\Query())
            ->select(['submission_id', 'cnt' => new \yii\db\Expression('COUNT(*)')])
            ->from('vote')
            ->groupBy('submission_id');

        return Submission::find()
            ->select(['submission.*', 'vote_stats.cnt'])
            ->from(['submission' => 'submission'])
            ->leftJoin(['vote_stats' => $subQuery], 'vote_stats.submission_id = submission.id')
            ->where(['not', ['submission.image1' => '']])
            // 🔥 ВАЖНО: указываем таблицу для vote_stats.cnt и submission.created_at
            ->orderBy(['vote_stats.cnt' => SORT_DESC, 'submission.created_at' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */




    /**
     * Список работ участников
     * @return string HTML-страница
     */

    public function actionContacts()
    {
        $model = new \app\models\ContactRequest();

        // 🔥 Если авторизован — подставляем ID и контакты
        if (!Yii::$app->user->isGuest) {
            $model->user_id = Yii::$app->user->id;
            $model->contacts = Yii::$app->user->identity->email;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('contactFormSubmitted', true);
                return $this->refresh();
            }
        }

        return $this->render('contacts', ['model' => $model]);
    }

    /**
     * Страница ввода кода подтверждения
     */
    /**
     * Отправка письма с кодом подтверждения
     */

    /**
     * Регистрация: сохраняем данные в сессию, отправляем код, НЕ создаём пользователя
     */
    public function actionSignup()
    {
        $form = new SignupForm();

        if (Yii::$app->request->isPost && $form->load(Yii::$app->request->post())) {
            Yii::info("📥 Form loaded: " . json_encode($form->attributes), __METHOD__);

            if ($form->validate()) {
                try {
                    $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                    $expire = time() + 900;

                    Yii::$app->session->set('pending_user', [
                        'username'  => $form->username,
                        'email'     => $form->email,
                        'name'      => $form->name,
                        'surname'   => $form->surname,
                        'password'  => Yii::$app->security->generatePasswordHash($form->password),
                    ]);
                    Yii::$app->session->set('pending_code', $code);
                    Yii::$app->session->set('pending_code_expire', $expire);

                    $this->sendVerificationEmail($form->email, $form->name, $code);

                    Yii::info("✅ Session saved, email sent. Redirecting...", __METHOD__);
                    Yii::$app->session->setFlash('success', '📧 Код отправлен на ' . Html::encode($form->email));
                    return $this->redirect(['/site/verify-code', 'email' => $form->email]);

                } catch (\Exception $e) {
                    Yii::error("❌ Signup exception: " . $e->getMessage(), __METHOD__);
                    Yii::$app->session->setFlash('error', '❌ Ошибка сервера: ' . $e->getMessage());
                }
            } else {
                Yii::warning("❌ Validation failed: " . json_encode($form->errors), __METHOD__);
            }
        }

        return $this->render('signup', ['us' => $form]);
    }

    /**
     * Проверка кода → только теперь создаём пользователя в БД
     */
    /**
     * Проверка кода → только теперь создаём пользователя в БД
     */
    public function actionVerifyCode()
    {
        $model = new VerifyCodeForm();

        // 1. Загружаем данные из POST (если форма отправлена)
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
        }

        // 2. Если email не пришёл в POST, берём из GET (переход по ссылке из письма)
        if (empty($model->email)) {
            $model->email = Yii::$app->request->get('email');
        }

        // 3. Проверка наличия email
        if (empty($model->email)) {
            Yii::$app->session->setFlash('error', '❌ Email не указан');
            return $this->redirect(['/site/signup']);
        }

        // 4. Проверка сессии
        $pending = Yii::$app->session['pending_user'] ?? null;
        $pendingCode = Yii::$app->session['pending_code'] ?? null;
        $pendingExpire = Yii::$app->session['pending_code_expire'] ?? null;

        if (!$pending || $model->email !== ($pending['email'] ?? null)) {
            Yii::$app->session->setFlash('error', '❌ Сессия регистрации истекла или email не совпадает');
            return $this->redirect(['/site/signup']);
        }

        // 5. Обработка отправки кода
        if (Yii::$app->request->isPost && $model->validate()) {
            if ($model->code === $pendingCode && time() <= $pendingExpire) {
                $user = new User();
                $user->username = $pending['username'];
                $user->email = $pending['email'];
                $user->name = $pending['name'];
                $user->surname = $pending['surname'];
                $user->password = $pending['password'];
                $user->status = User::STATUS_ACTIVE;
                $user->access_token = Yii::$app->security->generateRandomString();

                if ($user->save()) {
                    unset(Yii::$app->session['pending_user']);
                    unset(Yii::$app->session['pending_code']);
                    unset(Yii::$app->session['pending_code_expire']);

                    Yii::$app->session->setFlash('success', '🎉 Аккаунт создан! Войдите в систему.');
                    return $this->redirect(['/site/login']);
                } else {
                    Yii::$app->session->setFlash('error', '❌ Ошибка БД: ' . json_encode($user->getFirstErrors()));
                }
            } else {
                Yii::$app->session->setFlash('error', time() > $pendingExpire ? '⏳ Код истёк' : '❌ Неверный код');
            }
        }

        return $this->render('verify-code', ['model' => $model]);
    }

    /**
     * Повторная отправка кода (обновляет сессию)
     */
    public function actionResendCode($email)
    {
        // 1. Декодируем email, если он в base64
        if (base64_encode(base64_decode($email, true)) === $email) {
            $email = base64_decode($email);
        }

        // 2. Проверяем сессию
        $pending = Yii::$app->session->get('pending_user');
        if (!$pending || ($pending['email'] ?? '') !== $email) {
            Yii::$app->session->setFlash('error', '❌ Нет активной сессии регистрации');
            return $this->redirect(['/site/signup']);
        }

        // 3. Генерируем новый код
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expire = time() + 900; // 15 минут

        // 🔥 ПРАВИЛЬНОЕ сохранение в сессию (выбери один вариант):
        // Вариант А (рекомендую):
        Yii::$app->session->set('pending_code', $code);
        Yii::$app->session->set('pending_code_expire', $expire);

        // Вариант Б (тоже работает):
        // Yii::$app->session['pending_code'] = $code;
        // Yii::$app->session['pending_code_expire'] = $expire;

        // 4. Отправляем письмо
        $this->sendVerificationEmail($email, $pending['name'] ?? 'Пользователь', $code);

        Yii::$app->session->setFlash('success', '📧 Новый код отправлен на ' . Html::encode($email));
        return $this->redirect(['/site/verify-code', 'email' => $email]);
    }

    /**
     * Хелпер отправки письма (принимает массив вместо модели User)
     */
    private function sendVerificationEmail($email, $name, $code)
    {
        return Yii::$app->mailer->compose('emailVerifyCode', [
            'user' => ['email' => $email, 'name' => $name ?: 'Пользователь'],
            'code' => $code,
        ])
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setTo($email)
            ->setSubject('Код подтверждения регистрации')
            ->send();
    }
}
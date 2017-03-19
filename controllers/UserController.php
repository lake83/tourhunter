<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\ResetPasswordForm;
use app\models\RemindForm;
use yii\web\BadRequestHttpException;
use yii\base\InvalidParamException;
use app\models\SignupForm;
use app\components\SiteHelper;
use app\models\LoginForm;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use app\models\Transfers;
use app\models\TransfersSearch;

class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['profile', 'transfer'],
                'rules' => [
                    [
                        'actions' => ['profile', 'transfer'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post']
                ]
            ]
        ];
    }
    
    /**
     * Отправка письма для подтверждения E-mail пользователю.
     * 
     * @return string
     */
    public function actionEmailActivate()
    {
        if (Yii::$app->mailer->compose(['html' => 'emailActivation-html'], ['user' => Yii::$app->user->identity])
            ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
            ->setTo(Yii::$app->user->email)
            ->setSubject('Подтверждение email на ' . Yii::$app->name)
            ->send()) {
            Yii::$app->session->setFlash('success', 'Письмо для подтверждения E-mail отправлено.');    
        } else {
            Yii::$app->session->setFlash('error', 'Не удалось отправить письмо.');
        }
        return $this->redirect(SiteHelper::redirectByRole(Yii::$app->user->status));
    }
    
    /**
     * Подтверждение E-mail пользователя.
     * 
     * @return string
     * @throws BadRequestHttpException если не удалось
     */
    public function actionEmailConfirmation($token)
    {
        if (empty($token) || !is_string($token)) {
            throw new BadRequestHttpException('Токен не может быть пустым.');
        } else {
            $model = User::findOne(['auth_key' => $token]);
        }
        if (!$model || !$model->validateAuthKey($token)) {
            throw new BadRequestHttpException('Неправильный токен.');
        } else {
            $model->is_active = 1;
            if($model->save()) {
                Yii::$app->session->setFlash('success', 'Ваш E-mail подтвержден.');
                return Yii::$app->user->isGuest ? $this->goHome() : $this->redirect(SiteHelper::redirectByRole(Yii::$app->user->status));
            }
        }
    }
    
    /**
     * Запрос на восстановление пароля
     * 
     * @return string
     */
    public function actionRemind()
    {
        $this->layout = '@app/modules/admin/views/layouts/main-login';
        
        $model = new RemindForm;
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Инструкции отправлены на Ваш e-mail.');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при отправлении e-mail.');
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        return $this->render('remind', ['model' => $model]);
    }
    
    /**
     * Смена пароля
     * 
     * @param string $token токен для смены пароля
     * @return string
     * @throws BadRequestHttpException если не удалось
     */
    public function actionReset($token)
    {
        $this->layout = '@app/modules/admin/views/layouts/main-login';
        
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль сохранен.');

            return $this->redirect(['admin/user/index']);
        }
        return $this->render('reset', [
            'model' => $model
        ]);
    }
    
    /**
     * Login user action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['profile']);
        }
        return $this->render('login', ['model' => $model]);
    }
    
    /**
     * Регистрация пользователя.
     * 
     * @return string
     */
    public function actionRegistration()
    {
        $model = new SignupForm();
        
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                Yii::$app->session->setFlash('success', 'Инструкции по завершению регистрации отправлены на Ваш e-mail.');
                Yii::$app->user->login($user);
                return $this->redirect(SiteHelper::redirectByRole($user->status));
            }
        }
    }
    
    /**
     * Личный кабинет пользователя.
     * 
     * @return string
     */
    public function actionProfile()
    {
        $searchModel = new TransfersSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('profile', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Создание перевода.
     * 
     * @return string
     */
    public function actionTransfer()
    {
        $model = new Transfers;
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['profile']);
        }
        return $this->render('transfer', ['model' => $model]);
    }
}
?>
<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $password string */

$urlManager = Yii::$app->urlManager;
$activationLink = $urlManager->createAbsoluteUrl(['/user/email-confirmation', 'token' => $user->auth_key]);
?>

<table style="padding:0;background-color:#e5e5e5;width:650px;border-collapse:collapse;border-spacing:0; text-align:center; vertical-align:top; margin:0 auto;">
    <tbody>
        <tr style="padding:0;text-align:center;vertical-align:top;width:100%;" align="center">
            <td>                 
                <h1 style="padding:0 25px;color:#485671;font:400 24px Arial;margin-bottom:35px;margin-top:35px;width:100%;float:left;text-align:left;">Здравствуйте, <?= Html::encode($user->username) ?></h1>
                <div style="float: left;width: 60%;">
                    <span style="padding:0 25px;color:#4a5773;font:400 16px Arial;margin-bottom:30px;text-align:left;float:left;width:100%;">Вы успешно зарегистрировались на сайте <?= Yii::$app->name ?></span>
                    <span style="padding:0 25px;color:#4a5773;font:400 16px Arial;margin-bottom:30px;text-align:left;float:left;width:100%;">Мы создали вам аккаунт для более удобного использования нашего сервиса.</span>
                    <span style="padding:0 25px;color:#4a5773;font:400 16px Arial;margin-bottom:30px;text-align:left;float:left;width:100%;">Запомните Ваши данные для входа на сайт:</span>
                    <div style="margin-bottom:30px;float:left;width:100%;margin-left: 25px;">
                        <span style="float:left;color: #4a5773;font:700 16px Arial;margin-right:30px;">Логин:</span>
                        <span style="float:left;color: #4a5773;font:700 16px Arial;margin-right:30px;text-decoration:underline;"><?= $user->username ?></span>
                    </div>
                    <div style="margin-bottom: 30px;float:left;width:100%;margin-left: 25px;">
                        <span style="float:left;color: #4a5773;font:700 16px Arial;margin-right:30px;">Пароль:</span>
                        <span style="float:left;color: #4a5773;font:700 16px Arial;margin-right:30px;"><?= $password ?></span>
                    </div>
                    <a href="<?= $activationLink ?>" style="float:left;width:164px;height:40px;text-align:center;background-color:#1c69ff;color:#ffffff;font:400 16px Arial;text-transform:uppercase;text-decoration:none;line-height:40px;margin-bottom:30px;margin-right:15px;margin-left:25px;">Подтвердить</a>
                </div>
                <div style="text-align:center; width:40%;float:left;">
                    <img style="width:150px;height:150px;border-radius:50%;overflow:hidden;display:inline-block;margin-top:-55px;margin-bottom:25px;" src="<?= $urlManager->createAbsoluteUrl('/') ?>images/img-user.png" alt="user icon">
                </div>
                <hr style="border:0;height:1px;background-color:#687aa1;margin:5px 0 35px 0;float:left;width:calc(100% - 50px);margin-left:25px;">
                <span style="padding:0 25px;color:#4a5773;font:400 16px Arial;margin-bottom:30px;text-align:left;float:left;width:100%;">Если Вы не можете подтвердить запрос, пожалуйста, перейдите по ссылке или<br />вставьте ее в адресную строку браузера</span>
                <a href="<?= $activationLink ?>" style="float:left;color:#1c69ff;font:700 16px Arial;text-decoration:underline;padding:0 25px;margin-bottom:25px;"><?= $activationLink ?></a>
            </td>
        </tr>
    </tbody>            
</table>
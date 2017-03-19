<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $status;
    public $username;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            ['username', 'match', 'pattern' => '/^(([a-z\(\)\s]+)|([а-яё\(\)\s]+))$/isu'],
            ['username', 'string', 'min' => 3, 'max' => 25],
            ['username', 'trim'],
            ['username', 'unique', 'targetClass' => User::className(), 'message' => 'Этот логин уже зарегистрирован.'],

            ['email', 'email'],
            [['email'], 'string', 'max' => 100],
            ['email', 'trim'],
            
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Имя',
            'email' => 'E-mail',
            'password' => 'Пароль'
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User;
            $user->username = $this->username;
            $user->status = $this->status;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save() && !empty($this->email)) {
                Yii::$app->mailer->compose(['html' => 'newUser-html'], [
                    'user' => $user,
                    'password' => $user->password_hash
                ])
                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name])
                ->setTo($user->email)
                ->setSubject('Регистрация на ' . Yii::$app->name)
                ->send();
            }
            return $user;
        }
        return null;
    }
}
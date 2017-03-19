<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%transfers}}".
 *
 * @property integer $id
 * @property integer $from_id
 * @property integer $to_id
 * @property integer $sum
 * @property integer $created_at
 * @property integer $updated_at
 * 
 * @property User $userFrom
 * @property User $userTo
 */
class Transfers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transfers}}';
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['to_id', 'sum'], 'required'],
            [['from_id', 'created_at', 'updated_at'], 'integer'],
            ['sum', 'number', 'min' => 1],
            ['to_id', 'match', 'pattern' => '/^(([a-z\(\)\s]+)|([а-яё\(\)\s]+))$/isu'],
            ['to_id', 'compare', 'compareValue' => Yii::$app->user->name, 'operator' => '!==', 'message' => 'Перевод можно отправить только другим пользователям.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from_id' => 'От пользователя',
            'to_id' => 'Для пользователя',
            'sum' => 'Сумма',
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFrom()
    {
        return $this->hasOne(User::className(), ['id' => 'from_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTo()
    {
        return $this->hasOne(User::className(), ['id' => 'to_id']);
    }
    
    /**
     * @return array
     */
    public function getAllUsers()
    {
        return ArrayHelper::map(User::find()->where(['and', 'status=' . User::ROLE_USER, 'id!=' . Yii::$app->user->id])->all(), 'id', 'username');
    }
    
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $this->from_id = Yii::$app->user->id;
        
        if (!$user_to = User::find()->where(['username' => $this->to_id, 'status' => User::ROLE_USER])->one()) {
            $user_to = new SignupForm();
            $user_to->username = $this->to_id;
            $user_to->password = Yii::$app->security->generateRandomString(6);
            $user_to->status = User::ROLE_USER;
            $user_to = $user_to->signup();
        }
        $this->to_id = $user_to->id;
        
        return parent::beforeSave($insert);
    }
    
    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        $user_to = User::findOne($this->to_id);
        $user_to->balance = $user_to->balance + $this->sum;
        $user_to->save();
        
        $user_from = User::findOne($this->from_id);
        $user_from->balance = $user_from->balance - $this->sum;
        $user_from->save();
        
        parent::afterSave($insert, $changedAttributes);
    }
}
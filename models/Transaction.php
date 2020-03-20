<?php

namespace app\models;

use app\behaviors\transaction\UpdateBalanceBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "transaction".
 *
 * @property int    $id
 * @property int    $user_id
 * @property float  $amount
 * @property int    $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User   $user
 */
class Transaction extends ActiveRecord
{
    const STATUS_CANCEL  = 0;
    const STATUS_SUCCESS = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%transaction}}';
    }

    public function behaviors()
    {
        return [
            'updateBalances'          => [
                'class' => UpdateBalanceBehavior::class
            ],
            'timestampBehavior'       => [
                'class'      => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount'], 'required', 'message' => 'Укажите сумму'],
            [['amount'], 'double'],

            [['status'], 'in', 'range' => [self::STATUS_CANCEL, self::STATUS_SUCCESS]],

            [
                ['user'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => User::className(),
                'targetAttribute' => ['user_id' => 'id']
            ],

            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'user_id'    => 'ID пользователя',
            'userPhone'  => 'Телефон пользователя',
            'amount'     => 'Сумма',
            'status'     => 'Статус',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('transactions');
    }

    public function getUserPhone()
    {
        return ArrayHelper::getValue($this->user, 'phone');
    }

    public static function getTotal($provider, $fieldName)
    {
        $total = 0;

        foreach ($provider as $item) {
            $total += $item[$fieldName];
        }

        return $total;
    }
}

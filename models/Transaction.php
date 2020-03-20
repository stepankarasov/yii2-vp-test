<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

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
 * @property User $user
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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount'], 'required', 'message' => Yii::t('app', 'Укажите сумму.')],
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
            'id'         => Yii::t('app', 'ID'),
            'user_id'    => Yii::t('app', 'User ID'),
            'amount'     => Yii::t('app', 'Amount'),
            'status'     => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('transactions');
    }
}

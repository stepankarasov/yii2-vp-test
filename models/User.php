<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property int           $id
 * @property string        $last_name
 * @property string        $first_name
 * @property string        $phone
 * @property float         $balance
 * @property int           $status
 * @property string        $created_at
 * @property string        $updated_at
 *
 * @property string        $fullName
 * @property Transaction[] $transactions
 */
class User extends ActiveRecord
{
    const STATUS_ACTIVE   = 1;
    const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['last_name'], 'match', 'pattern' => '/^[a-zA-Zа-яёА-ЯЁ\s\-]+$/u'],
            [['last_name'], 'required', 'message' => Yii::t('app', 'Укажите фамилию.')],
            [['last_name'], 'string', 'min' => 2, 'max' => 50],

            [['first_name'], 'match', 'pattern' => '/^[a-zA-Zа-яёА-ЯЁ\s\-]+$/u'],
            [['first_name'], 'required', 'message' => Yii::t('app', 'Укажите имя.')],
            [['first_name'], 'string', 'min' => 2, 'max' => 50],

            [['phone'], 'required'],
            [['phone'], 'string', 'min' => 10, 'max' => 20],
            [
                ['phone'],
                'unique',
                'targetClass' => self::className(),
                'message'     => Yii::t('app', 'Номер телефона уже используется.')
            ],
            [
                ['phone'],
                'match',
                'pattern' => '/^(\+)?(\(\d{2,3}\) ?\d|\d)(([ \-]?\d)|( ?\(\d{2,3}\) ?)){5,12}\d$/',
                'message' => Yii::t('app', 'Укажите правильный номер телефона')
            ],

            [['status'], 'in', 'range' => [self::STATUS_INACTIVE, self::STATUS_ACTIVE]],

            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'             => Yii::t('app', 'ID'),
            'last_name'      => Yii::t('app', 'Last Name'),
            'first_name'     => Yii::t('app', 'First Name'),
            'phone'          => Yii::t('app', 'Phone'),
            'balance'        => Yii::t('app', 'Balance'),
            'status'         => Yii::t('app', 'Status'),
            'created_at'     => Yii::t('app', 'Created At'),
            'updated_at'     => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['id' => 'user_id'])->inverseOf('user');
    }
}

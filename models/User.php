<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
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

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
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
            [['last_name'], 'match', 'pattern' => '/^[a-zA-Zа-яёА-ЯЁ\s\-]+$/u'],
            [['last_name'], 'required', 'message' => 'Укажите фамилию'],
            [['last_name'], 'string', 'min' => 2, 'max' => 50],

            [['first_name'], 'match', 'pattern' => '/^[a-zA-Zа-яёА-ЯЁ\s\-]+$/u'],
            [['first_name'], 'required', 'message' => 'Укажите имя'],
            [['first_name'], 'string', 'min' => 2, 'max' => 50],

            [['phone'], 'required'],
            [['phone'], 'string', 'min' => 10, 'max' => 20],
            [
                ['phone'],
                'unique',
                'targetClass' => self::className(),
                'message'     => 'Номер телефона уже используется'
            ],
            [
                ['phone'],
                'match',
                'pattern' => '/^(\+)?(\(\d{2,3}\) ?\d|\d)(([ \-]?\d)|( ?\(\d{2,3}\) ?)){5,12}\d$/',
                'message' => 'Укажите правильный номер телефона'
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
            'id'         => 'ID',
            'last_name'  => 'Фамилия',
            'first_name' => 'Имя',
            'full_name'  => 'ФИО',
            'phone'      => 'Телефон',
            'balance'    => 'Баланс',
            'status'     => 'Статус',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
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

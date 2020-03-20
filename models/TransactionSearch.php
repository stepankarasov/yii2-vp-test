<?php

namespace app\models;

use DateTime;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TransactionSearch represents the model behind the search form of `app\models\Transaction`.
 */
class TransactionSearch extends Transaction
{
    private $userPhone;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status'], 'integer'],
            [['amount'], 'double'],
            [['userPhone', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     * @throws \Exception
     */
    public function search($params)
    {
        $query = Transaction::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $createdAt = explode(' - ', $this->created_at);
        if (count($createdAt) == 2) {
            $dateStart = (new DateTime($createdAt[0]))->setTime(00, 00, 00)->getTimestamp();
            $dateEnd = (new DateTime($createdAt[1]))->setTime(23, 59, 59)->getTimestamp();

            $query->andWhere(['between', 'transaction.created_at', $dateStart, $dateEnd]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'transaction.id'         => $this->id,
            'transaction.user_id'    => $this->user_id,
            'transaction.amount'     => $this->amount,
            'transaction.status'     => $this->status,
        ]);

        if ($this->userPhone) {
            $query->joinWith([
                'user' => function ($q)
                {
                    $q->where('user.phone LIKE "%' .
                        $this->userPhone . '%"');
                }
            ]);
        }

        return $dataProvider;
    }

    public function setUserPhone($phone)
    {
        $this->userPhone = $phone;
    }
}

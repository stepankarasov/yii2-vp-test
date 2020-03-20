<?php

namespace app\models;

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id'         => $this->id,
            'user_id'    => $this->user_id,
            'amount'     => $this->amount,
            'transaction.status'     => $this->status,
            'transaction.created_at' => $this->created_at,
            'transaction.updated_at' => $this->updated_at,
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

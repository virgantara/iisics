<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Payment;

/**
 * PaymentSearch represents the model behind the search form of `app\models\Payment`.
 */
class PaymentSearch extends Payment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pay_id', 'pay_nominal', 'valid_by'], 'integer'],
            [['pay_created', 'pay_date', 'pay_file', 'pay_method', 'pay_origin', 'pay_destination', 'pay_currency', 'pay_info', 'pay_status', 'valid_by_name','pid', 'abs_id'], 'safe'],
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
        $query = Payment::find();
        $query->alias('t');
        $query->joinWith(['abs as a','p as participant']);

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
            'pay_id' => $this->pay_id,
            'pay_created' => $this->pay_created,
            'pay_date' => $this->pay_date,
            'pay_nominal' => $this->pay_nominal,
            'valid_by' => $this->valid_by,
        ]);

        $query->andFilterWhere(['like', 'pay_file', $this->pay_file])
            ->andFilterWhere(['like', 'pay_method', $this->pay_method])
            ->andFilterWhere(['like', 'participant.name', $this->pid])
            ->andFilterWhere(['like', 'a.abs_title', $this->abs_id])
            ->andFilterWhere(['like', 'pay_origin', $this->pay_origin])
            ->andFilterWhere(['like', 'pay_destination', $this->pay_destination])
            ->andFilterWhere(['like', 'pay_currency', $this->pay_currency])
            ->andFilterWhere(['like', 'pay_info', $this->pay_info])
            ->andFilterWhere(['like', 'pay_status', $this->pay_status])
            ->andFilterWhere(['like', 'valid_by_name', $this->valid_by_name]);

        if(Yii::$app->user->identity->access_role == 'participant'){
            $query->andWhere([
                't.pid' => Yii::$app->user->identity->pid
            ]);
        }

        return $dataProvider;
    }
}

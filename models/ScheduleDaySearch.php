<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ScheduleDay;

/**
 * ScheduleDaySearch represents the model behind the search form of `app\models\ScheduleDay`.
 */
class ScheduleDaySearch extends ScheduleDay
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sequence'], 'integer'],
            [['day_name'], 'safe'],
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
        $query = ScheduleDay::find();

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
            'id' => $this->id,
            'sequence' => $this->sequence,
        ]);

        $query->andFilterWhere(['like', 'day_name', $this->day_name]);

        return $dataProvider;
    }
}

<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ScheduleTime;

/**
 * ScheduleTimeSearch represents the model behind the search form of `app\models\ScheduleTime`.
 */
class ScheduleTimeSearch extends ScheduleTime
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'day_id'], 'integer'],
            [['waktu_mulai', 'waktu_selesai', 'agenda', 'description'], 'safe'],
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
        $query = ScheduleTime::find();

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
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_selesai' => $this->waktu_selesai,
            'day_id' => $this->day_id,
        ]);

        $query->andFilterWhere(['like', 'agenda', $this->agenda])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}

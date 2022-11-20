<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Topics;

/**
 * TopicsSearch represents the model behind the search form of `app\models\Topics`.
 */
class TopicsSearch extends Topics
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['topic_id'], 'integer'],
            [['topic_title'], 'safe'],
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
        $query = Topics::find();

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
            'topic_id' => $this->topic_id,
        ]);

        $query->andFilterWhere(['like', 'topic_title', $this->topic_title]);

        return $dataProvider;
    }
}

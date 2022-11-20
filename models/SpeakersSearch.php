<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Speakers;

/**
 * SpeakersSearch represents the model behind the search form of `app\models\Speakers`.
 */
class SpeakersSearch extends Speakers
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['speaker_id', 'sequence'], 'integer'],
            [['speaker_name', 'speaker_slug', 'speaker_type', 'speaker_institution', 'speaker_content', 'speaker_image'], 'safe'],
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
        $query = Speakers::find();

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
            'speaker_id' => $this->speaker_id,
            'sequence' => $this->sequence,
        ]);

        $query->andFilterWhere(['like', 'speaker_name', $this->speaker_name])
            ->andFilterWhere(['like', 'speaker_slug', $this->speaker_slug])
            ->andFilterWhere(['like', 'speaker_type', $this->speaker_type])
            ->andFilterWhere(['like', 'speaker_institution', $this->speaker_institution])
            ->andFilterWhere(['like', 'speaker_content', $this->speaker_content])
            ->andFilterWhere(['like', 'speaker_image', $this->speaker_image]);

        return $dataProvider;
    }
}

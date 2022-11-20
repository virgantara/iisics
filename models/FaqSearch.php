<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Faq;

/**
 * FaqSearch represents the model behind the search form of `app\models\Faq`.
 */
class FaqSearch extends Faq
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['faq_id', 'faq_sequence'], 'integer'],
            [['faq_question', 'faq_answer'], 'safe'],
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
        $query = Faq::find();

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
            'faq_id' => $this->faq_id,
            'faq_sequence' => $this->faq_sequence,
        ]);

        $query->andFilterWhere(['like', 'faq_question', $this->faq_question])
            ->andFilterWhere(['like', 'faq_answer', $this->faq_answer]);

        return $dataProvider;
    }
}

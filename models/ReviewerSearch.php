<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Reviewer;

/**
 * ReviewerSearch represents the model behind the search form of `app\models\Reviewer`.
 */
class ReviewerSearch extends Reviewer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rev_id'], 'integer'],
            [['rev_email', 'rev_password', 'rev_type', 'rev_name', 'rev_status', 'rev_enc'], 'safe'],
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
        $query = Reviewer::find();

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
            'rev_id' => $this->rev_id,
        ]);

        $query->andFilterWhere(['like', 'rev_email', $this->rev_email])
            ->andFilterWhere(['like', 'rev_password', $this->rev_password])
            ->andFilterWhere(['like', 'rev_type', $this->rev_type])
            ->andFilterWhere(['like', 'rev_name', $this->rev_name])
            ->andFilterWhere(['like', 'rev_status', $this->rev_status])
            ->andFilterWhere(['like', 'rev_enc', $this->rev_enc]);

        return $dataProvider;
    }
}

<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AbstractReview;

/**
 * AbstractReviewSearch represents the model behind the search form of `app\models\AbstractReview`.
 */
class AbstractReviewSearch extends AbstractReview
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['rev_id', 'abs_id','status','response','comments'], 'safe'],
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

    public function searchMyReview($params)
    {
        $query = AbstractReview::find();
        $query->alias('t');

        $query->joinWith(['abs as a','rev as r']);

        if(Yii::$app->user->identity->access_role == 'reviewer'){
            $query->andWhere([
                't.rev_id' => Yii::$app->user->identity->rev_id
            ]);
        }

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

        $query->andFilterWhere([
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'a.abs_title', $this->abs_id]);
        $query->andFilterWhere(['like', 'r.name', $this->rev_id]);
        $query->andFilterWhere(['like', 'response', $this->response]);
        $query->andFilterWhere(['like', 'comments', $this->comments]);

        return $dataProvider;
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
        $query = AbstractReview::find();

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
            'rev_id' => $this->rev_id,
            'abs_id' => $this->abs_id,
        ]);

        return $dataProvider;
    }
}

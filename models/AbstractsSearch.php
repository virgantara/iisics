<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Abstracts;

/**
 * AbstractsSearch represents the model behind the search form of `app\models\Abstracts`.
 */
class AbstractsSearch extends Abstracts
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['abs_id', 'pid', 'topic_id', 'abs_paid', 'rev_id', 'viewed'], 'integer'],
            [['abs_date', 'abs_date_edit', 'abs_title', 'abs_author', 'abs_institution', 'abs_abstract', 'abs_keyword', 'abs_type', 'abs_status', 'presenter_name', 'examiner_by', 'rev_name'], 'safe'],
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
        $query = Abstracts::find();

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
            'abs_id' => $this->abs_id,
            'pid' => $this->pid,
            'topic_id' => $this->topic_id,
            'abs_date' => $this->abs_date,
            'abs_date_edit' => $this->abs_date_edit,
            'abs_paid' => $this->abs_paid,
            'rev_id' => $this->rev_id,
            'viewed' => $this->viewed,
        ]);

        

        $query->andFilterWhere(['like', 'abs_title', $this->abs_title])
            ->andFilterWhere(['like', 'abs_author', $this->abs_author])
            ->andFilterWhere(['like', 'abs_institution', $this->abs_institution])
            ->andFilterWhere(['like', 'abs_abstract', $this->abs_abstract])
            ->andFilterWhere(['like', 'abs_keyword', $this->abs_keyword])
            ->andFilterWhere(['like', 'abs_type', $this->abs_type])
            ->andFilterWhere(['like', 'abs_status', $this->abs_status])
            ->andFilterWhere(['like', 'presenter_name', $this->presenter_name])
            ->andFilterWhere(['like', 'examiner_by', $this->examiner_by])
            ->andFilterWhere(['like', 'rev_name', $this->rev_name]);

        if(Yii::$app->user->identity->access_role == 'participant'){
            $query->andWhere([
                'pid' => Yii::$app->user->identity->pid,
            ]);            
        }
        return $dataProvider;
    }
}

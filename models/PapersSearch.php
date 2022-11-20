<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Papers;

/**
 * PapersSearch represents the model behind the search form of `app\models\Papers`.
 */
class PapersSearch extends Papers
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paper_id', 'paper_final', 'paper_reviewed'], 'integer'],
            [['paper_file', 'paper_raw', 'paper_ext', 'paper_date', 'paper_info', 'paper_status', 'paper_editor_comment', 'paper_recomendation', 'paper_review_comment', 'paper_review_date', 'paper_review_file', 'paper_review_file_raw', 'paper_review_file_ext', 'paper_revised_file', 'paper_revised_file_raw', 'paper_revised_file_ext', 'paper_final_file', 'paper_final_file_raw', 'paper_final_file_ext','abs_id', 'pid'], 'safe'],
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
        $query = Papers::find();
        $query->alias('t');
        $query->joinWith(['p as p','abs as a']);

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
            'paper_id' => $this->paper_id,
            'paper_date' => $this->paper_date,
            'paper_final' => $this->paper_final,
            'paper_reviewed' => $this->paper_reviewed,
            'paper_review_date' => $this->paper_review_date,
        ]);

        $query->andFilterWhere(['like', 'p.name', $this->pid])
            ->andFilterWhere(['like', 'a.abs_title', $this->abs_id])
            ->andFilterWhere(['like', 'paper_ext', $this->paper_ext])
            ->andFilterWhere(['like', 'paper_info', $this->paper_info])
            ->andFilterWhere(['like', 'paper_status', $this->paper_status])
            ->andFilterWhere(['like', 'paper_editor_comment', $this->paper_editor_comment])
            ->andFilterWhere(['like', 'paper_recomendation', $this->paper_recomendation])
            ->andFilterWhere(['like', 'paper_review_comment', $this->paper_review_comment])
            ->andFilterWhere(['like', 'paper_review_file', $this->paper_review_file])
            ->andFilterWhere(['like', 'paper_review_file_raw', $this->paper_review_file_raw])
            ->andFilterWhere(['like', 'paper_review_file_ext', $this->paper_review_file_ext])
            ->andFilterWhere(['like', 'paper_revised_file', $this->paper_revised_file])
            ->andFilterWhere(['like', 'paper_revised_file_raw', $this->paper_revised_file_raw])
            ->andFilterWhere(['like', 'paper_revised_file_ext', $this->paper_revised_file_ext])
            ->andFilterWhere(['like', 'paper_final_file', $this->paper_final_file])
            ->andFilterWhere(['like', 'paper_final_file_raw', $this->paper_final_file_raw])
            ->andFilterWhere(['like', 'paper_final_file_ext', $this->paper_final_file_ext]);

        if(Yii::$app->user->identity->access_role == 'participant'){
            $query->andWhere([
                't.pid' => Yii::$app->user->identity->pid
            ]);
        }

        return $dataProvider;
    }
}

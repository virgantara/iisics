<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\System;

/**
 * SystemSearch represents the model behind the search form of `app\models\System`.
 */
class SystemSearch extends System
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sys_id'], 'integer'],
            [['sys_name', 'sys_content'], 'safe'],
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
        $query = System::find();

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
            'sys_id' => $this->sys_id,
        ]);

        $query->andFilterWhere(['like', 'sys_name', $this->sys_name])
            ->andFilterWhere(['like', 'sys_content', $this->sys_content]);

        return $dataProvider;
    }
}

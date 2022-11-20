<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Certificate;

/**
 * CertificateSearch represents the model behind the search form of `app\models\Certificate`.
 */
class CertificateSearch extends Certificate
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cert_id', 'pid', 'abs_id', 'type_id'], 'integer'],
            [['cert_no', 'name'], 'safe'],
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
        $query = Certificate::find();

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
            'cert_id' => $this->cert_id,
            'pid' => $this->pid,
            'abs_id' => $this->abs_id,
            'type_id' => $this->type_id,
        ]);

        $query->andFilterWhere(['like', 'cert_no', $this->cert_no])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}

<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CertificateType;

/**
 * CertificateTypeSearch represents the model behind the search form of `app\models\CertificateType`.
 */
class CertificateTypeSearch extends CertificateType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['certificate_type_name', 'certificate_prefix_number', 'certificate_template'], 'safe'],
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
        $query = CertificateType::find();

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
        ]);

        $query->andFilterWhere(['like', 'certificate_type_name', $this->certificate_type_name])
            ->andFilterWhere(['like', 'certificate_prefix_number', $this->certificate_prefix_number])
            ->andFilterWhere(['like', 'certificate_template', $this->certificate_template]);

        return $dataProvider;
    }
}

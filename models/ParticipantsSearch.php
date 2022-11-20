<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Participants;

/**
 * ParticipantsSearch represents the model behind the search form of `app\models\Participants`.
 */
class ParticipantsSearch extends Participants
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'active', 'paid', 'enable', 'certificate', 'as_presenter', 'block'], 'integer'],
            [['participant_id', 'name', 'name2', 'gender', 'type', 'institution', 'address', 'country', 'phone', 'fax', 'email', 'password', 'registered', 'token', 'reset_key', 'activation_code', 'status', 'regsuccess', 'no_certificate'], 'safe'],
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
        $query = Participants::find();

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
            'pid' => $this->pid,
            'registered' => $this->registered,
            'active' => $this->active,
            'paid' => $this->paid,
            'enable' => $this->enable,
            'certificate' => $this->certificate,
            'as_presenter' => $this->as_presenter,
            'block' => $this->block,
        ]);

        $query->andFilterWhere(['like', 'participant_id', $this->participant_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name2', $this->name2])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'institution', $this->institution])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'reset_key', $this->reset_key])
            ->andFilterWhere(['like', 'activation_code', $this->activation_code])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'regsuccess', $this->regsuccess])
            ->andFilterWhere(['like', 'no_certificate', $this->no_certificate]);

        return $dataProvider;
    }
}

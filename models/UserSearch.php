<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `backend\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['username','fullname','status', 'auth_key', 'password_hash', 'password_reset_token', 'email','access_role'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = User::find();
        $query->alias('u');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => [
                'access_role' => SORT_ASC,
                'fullname' => SORT_ASC
            ]],
        ]);

        if(Yii::$app->user->identity->access_role != 'theCreator')
        {
            $query->andWhere(['<>','access_role','theCreator']);
        }        

        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

       

        $query->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email]);

        $query->andFilterWhere(['access_role' => $this->access_role]);
        $query->andFilterWhere(['status' => $this->status]);
        

        return $dataProvider;
    }

    // public static function getListRoles()
    // {
    //     $list = \app\rbac\models\AuthItem::find()->all();

    //     return $list;
    // } 
}

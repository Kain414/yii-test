<?php

namespace app\models\search;

use app\models\User;
use yii\data\ActiveDataProvider;

class UserSearch extends User {

    public function rules()
    { 
        return [
            [['id', 'status'], 'integer'],
            ['email', 'email'],
            ['username', 'safe'],
        ];
    }

	public function search($params) {
		$query = User::find();

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => 10,
			],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

		$query->andFilterWhere(['id' => $this->id])
        	->andFilterWhere(['email' => $this->email])
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['username' => $this->username]);

        return $dataProvider;
	}

	
}
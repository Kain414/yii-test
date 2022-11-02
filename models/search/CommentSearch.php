<?php

namespace app\models\search;

use app\models\Comment;
use yii\data\ActiveDataProvider;

class CommentSearch extends Comment {

    public function rules()
    { 
        return [
            [['blog_id'], 'integer'],
        ];
    }

	public function search($params) {
		$query = Comment::find();

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => 20,
			],
        ]);

        // if (!($this->load($params) && $this->validate())) {
        //     return $dataProvider;
        // }

		$query->andFilterWhere(['blog_id' => $params['id']]);

        return $dataProvider;
	}

	
}
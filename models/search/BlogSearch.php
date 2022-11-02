<?php

namespace app\models\search;

use app\models\Blog;
use yii\data\ActiveDataProvider;

class BlogSearch extends Blog {

    public function rules()
    { 
        return [
            [['id'], 'integer'],
            ['open', 'boolean'],
            [['title', 'body', 'author', 'info'],'string'],
            ['status', 'integer'],
        ];
    }

	public function search($params = null) {
		$query = Blog::find();

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
        	->andFilterWhere(['open' => $this->open])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['author' => $this->author]);

        return $dataProvider;
	}
    public function searchForPublic() {
		$query = Blog::find();

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => 10,
			],
        ]);

		$query->FilterWhere(['status' => 1])
        ->orFilterWhere(['status' => 4]);

        return $dataProvider;
	}

    public function searchForMyPosts() {
        $query = Blog::find();

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => 10,
			],
        ]);

		$query->andFilterWhere(['id' => $this->id])
        	->andFilterWhere(['open' => $this->open])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['author' => $this->author]);

        return $dataProvider;
    }

    public function searchForModerator() {
        $query = Blog::find();

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => 10,
			],
        ]);

		$query->FilterWhere(['status' => 4])
            ->orFilterWhere(['status' => 0]);

        return $dataProvider;
    }

    

	
}
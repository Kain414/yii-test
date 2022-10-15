<?php

namespace app\models\search;

use app\models\User;

class UserSearch extends User {

	public function search() {
		$query = User::find();

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
	}
}
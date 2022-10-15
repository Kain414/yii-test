<?php

namespace app\models\search;

use app\models\Log;
use yii\data\ActiveDataProvider;

class LogSearch extends Log {

	public function search() {
		$query = Log::find();

		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
	}

}
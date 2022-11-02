<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Like extends ActiveRecord {

    public function rules () {
        return [
            [['blog_id', 'user_id', 'status'], 'required'],
            [['blog_id', 'user_id', 'status'], 'integer'],
        ];
    }

    public static function tableName() {
        return 'blogs_likes';
    }

    public function getStatus() {
        $like = self::findOne(['blog_id' => $this->blog_id, 'user_id' => $this->user_id]);
        if ($like != null) {
            return $like->status;
        }

        return null;
    }

    public function setStatus($blog_id) {
        $sign = $blog_id <=> 0;
        $blog_id = abs($blog_id);
        $like = self::findOne(['blog_id' => $this->blog_id, 'user_id' => $this->user_id]);
        $blog = Blog::findOne(['id' => $this->blog_id]);
        if ($like != null) {
            if ($like->status == 1) {
                if ($sign == 1) {
                    $blog->likes = $blog->likes - 1;
                    $blog->save();
                    $like->deleteAll();
                    return true;
                }
                $blog->likes = $blog->likes - 1;
                $blog->dislikes = $blog->dislikes + 1;
                $blog->save();
                $like->status = 2;
                $like->save();
                return true;
            } else {
                if ($sign == -1) {
                    $blog->dislikes = $blog->dislikes - 1;
                    $blog->save();
                    $like->deleteAll();
                    return true;
                }
                $blog->likes = $blog->likes + 1;
                $blog->dislikes = $blog->dislikes - 1;
                $blog->save();
                $like->status = 1;
                $like->save();
                return true;
            }
        } else {
            if ($sign == 1) {
                $blog->likes = $blog->likes + 1;
                $blog->save();
                $this->status = 1;
                return false;
            }
            $blog->dislikes = $blog->dislikes + 1;
            $blog->save();
            $this->status = 2;
            return false;
        }
    }
    
    
}
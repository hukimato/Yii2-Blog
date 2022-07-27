<?php

namespace app\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "tag".
 *
 * @property int $id
 * @property string|null $title
 *
 * @property ArticleTag[] $articleTags
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
        ];
    }

    /**
     * Gets query for [[ArticleTags]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticleTags()
    {
        return $this->hasMany(ArticleTag::className(), ['tag_id' => 'id']);
    }

    public function getArticles()
    {
        return $this->hasMany(Article::class, ['id' => 'article_id'])
            ->viaTable('article_tag', ['tag_id' => 'id']);
    }

    public static function getArticlesByTag($id)
    {
        $article_ids = ArticleTag::find()->select('article_id')->where(['tag_id' => $id]);
        $query = Article::find()->where(['id' => $article_ids]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 3]);
        $articles = $query->offset($pagination->offset)->limit($pagination->limit)->all();
        return  ['articles' => $articles, 'pagination'=>$pagination];
    }
}

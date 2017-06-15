<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;

class ArticleController extends \yii\web\Controller
{
    //显示文章列表
    public function actionIndex()
    {
        $query = Article::find();
        $pager = new Pagination([
            'totalCount'=>$query->count(),
            'pageSize'=>2
        ]);
        $articles = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['articles'=>$articles,'pager'=>$pager]);
    }
    //添加文章
    public function actionAdd()
    {
        $article = new Article();
        $article_detail = new ArticleDetail();
        if($article->load(\Yii::$app->request->post())
            && $article_detail->load(\Yii::$app->request->post())
            && $article->validate()
            && $article_detail->validate())
        {
            $article->save();
            $article_detail->article_id = $article->id;
            $article_detail->save();

            \Yii::$app->session->setFlash('success','文章添加成功');
            return $this->redirect(['article/index']);
        }
        return $this->render('add',['article'=>$article,'article_detail'=>$article_detail]);
    }

    //修改文章

    //查看文章
    public function actionView($id)
    {
        $model = Article::findOne($id);

        return $this->render('view',['model'=>$model]);
    }

}

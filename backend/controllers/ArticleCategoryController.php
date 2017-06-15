<?php

namespace backend\controllers;

use backend\models\ArticleCategory;

class ArticleCategoryController extends \yii\web\Controller
{
    //显示文章分类列表
    public function actionIndex()
    {   $models = ArticleCategory::find()->all();
        return $this->render('index',['models'=>$models]);
    }
    //添加文章分类
    public function actionAdd()
    {
        $model = new ArticleCategory();
        if($model->load(\Yii::$app->request->post()) && $model->validate())
        {
            $model->save();
            \Yii::$app->session->setFlash('success','文章分类添加成功');
            return $this->redirect(['article-category/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改文章分类
    public function actionEdit($id)
    {
        $model = ArticleCategory::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post()) && $model->validate())
        {
            $model->save();
            \Yii::$app->session->setFlash('success','文章分类添加成功');
            return $this->redirect(['article-category/index']);
        }
        return $this->render('add',['model'=>$model]);
    }
}

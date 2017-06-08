<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {   $brands = Brand::find()->all();
        return $this->render('index',['brands'=>$brands]);
    }
    //添加品牌
    public function actionAdd()
    {
        $model = new Brand();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            //验证
            if($model->validate()){
                //保存图片
                $fileName = '/images/'.uniqid().'.'.$model->imgFile->extension;
                $model->imgFile->saveAs(\yii::getAlias('@webroot').$fileName,false);
                $model->logo = $fileName;
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success','品牌添加成功');
                return $this->redirect(['brand/index']);
            }
        }

        return $this->render('add',['model'=>$model]);
    }
}

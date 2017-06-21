<?php

namespace backend\controllers;

use backend\components\RbacFilter;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;
use crazyfd\qiniu\Qiniu;


class BrandController extends \yii\web\Controller
{
    //使用过滤器，没权限不能通过url跳转访问
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
    //分页分类列表
    public function actionIndex()
    {
        $query = Brand::find()->where(['!=','status',-1]);

        $pager = new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>3
        ]);
        $brands = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['brands'=>$brands,'pager'=>$pager]);
    }
    //添加品牌
    public function actionAdd()
    {
        $model = new Brand();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            //$model->imgFile = UploadedFile::getInstance($model,'imgFile');
            //验证
            if($model->validate()){
                //保存图片
//                $fileName = '/images/'.uniqid().'.'.$model->imgFile->extension;
//                $model->imgFile->saveAs(\yii::getAlias('@webroot').$fileName,false);
//                $model->logo = $fileName;
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success','品牌添加成功');
                return $this->redirect(['brand/index']);
            }
        }

        return $this->render('add',['model'=>$model]);
    }
    //修改品牌
    public function actionEdit($id)
    {
        $model = Brand::findone(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('品牌不存在');
        }
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
//            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            //验证
            if($model->validate()){
                //保存图片
//                $fileName = '/images/'.uniqid().'.'.$model->imgFile->extension;
//                $model->imgFile->saveAs(\yii::getAlias('@webroot').$fileName,false);
//                $model->logo = $fileName;
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success','品牌添加成功');
                return $this->redirect(['brand/index']);
            }
        }

        return $this->render('add',['model'=>$model]);
    }
    //删除品牌
    public function actionDel($id)
    {
        $model = Brand::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('品牌不存在');
        }
        $model->updateAttributes(['status'=>-1]);
        \yii::$app->session->setFlash('success','品牌删除成功');
        return $this->redirect(['brand/index']);
    }
    //上传图片到七牛云
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $imgUrl = $action->getWebUrl();
                    //调用七牛云组件，将图片上传到七牛云
                    $qiniu = \Yii::$app->qiniu;
//                  $qiniu->uploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);
                    $qiniu->uploadFile($action->getSavePath(),$imgUrl);
                    //获取改图片在七牛云的地址
                    $url = $qiniu->getLink($imgUrl);
                    $action->output['fileUrl'] = $url;
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
//    public function actionTest()
//    {
//        $ak = 'f8fs5H5cwZZk9iKTBrUTNZaF-hUfQNMtjxYXh0D1';
//        $sk = '48cepcr2jmrtCJDqzYNdpkQM-Rp5nnyebXl6qETC';
//        $domain = 'http://or9tdz5h7.bkt.clouddn.com/';
//        $bucket = 'momota';
//        $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
//        //上传的文件
//        $fileName = \yii::getAlias('@webroot').'/upload/1.jpg';
//        $key = '1.jpg';
//        $rs = $qiniu->uploadFile($fileName,$key);
//        $url = $qiniu->getLink($key);
//        var_dump($url);
//    }
}
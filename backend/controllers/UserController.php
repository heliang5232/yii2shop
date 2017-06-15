<?php

namespace backend\controllers;

use backend\models\LoginForm;

class UserController extends \yii\web\Controller
{
    public function actionAdd()
    {

    }

    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4,
            ],
        ];
    }

    public function actionUser()
    {
        //实例化user组件
        $user = \Yii::$app->user;
        $user->isGuest;
    }
    public function actionLogin()
    {
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //跳转到登录检测页
                return $this->redirect(['Account/user']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }
    public function actionLogout()
    {
        \Yii::$app->user->logout();
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/3
 * Time: 11:18
 */

namespace backend\controllers;


use backend\models\Admin;
use backend\models\LoginForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AdminController extends Controller
{
    public $defaultAction = 'login';//默认网站打开路径

    /*
     * 添加管理员
     */
    public function actionInit()
    {

        $admin = new Admin();
        $admin->username = 'admin';
        $admin->password = '12345678';
        $admin->email = 'admin@admin.com';
        $admin->auth_key = \Yii::$app->security->generateRandomString();
        $admin->save();
        return $this->redirect(['admin/login']);
        //注册完成后自动帮用户登录账号
        //\Yii::$app->user->login($admin);
    }


    //添加管理员
    public function actionAdd()
    {
        $model = new Admin(['scenario'=>Admin::SCENARIO_ADD]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            if($model->addUser($model->id)){
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['admin/index']);
            }

        }
        return $this->render('add',['model'=>$model]);
    }

    //修改
    public function actionEdit($id)
    {
        $model = Admin::findOne(['id'=>$id]);
        $authManager = \Yii::$app->authManager;
        $roles = $authManager->getRolesByUser($id);
        $model->loadData($roles);
        if($model==null){
            throw new NotFoundHttpException('账号不存在');
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->updateRole($id)){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['admin/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDelete($id)
    {
        $role = \Yii::$app->authManager->getRolesByUser($id);
        if($role==null){
            throw new NotFoundHttpException('用户不存在');
        }
        \Yii::$app->authManager->revokeAll($id);
        Admin::findOne(['id'=>$id])->delete();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['admin/index']);
    }

    //退出
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['admin/login']);
    }

    //登录
    public function actionLogin()
    {
        $model = new LoginForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->login()){
                \Yii::$app->session->setFlash('success','登录成功');
                return $this->redirect(['admin/index']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }

    //用户列表
    public function actionIndex(){
    $models = Admin::find()->all();
        return $this->render('index',['models'=>$models]);
    }
}
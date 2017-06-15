<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_login_time
 * @property string $last_login_ip
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at', 'last_login_time', 'last_login_ip'], 'required'],
            [['status', 'created_at', 'updated_at', 'last_login_time'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'last_login_ip'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            ['code','captcha','captchaAction'=>'user/captcha']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => '密码',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_login_time' => '最后登陆时间',
            'last_login_ip' => '最后登陆ip',
            'code'=>'验证码'
        ];
    }

    public static function findIdentity($id)
    {
        // ͨ��id��ȡ�˺�
        return self::findOne(['id'=>$id]);
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        // ��ȡ��ǰ�˺ŵ�id
        return $this->id;
    }


    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }


    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    //自定义验证方法
    public function validateUsername(){
        $user = User::findOne(['username'=>$this->username]);
        if($user){
            //用户存在 验证密码
            if($this->auth_key != $user->auth_key){
                $this->addError('password','密码不正确');
            }else{
                //账号秘密正确，登录
                \Yii::$app->user->login($user);
            }
        }else{
            //账号不存在  添加错误
            $this->addError('username','账号不正确');

        }
    }
}

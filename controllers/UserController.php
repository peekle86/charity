<?php


namespace app\controllers;

use app\models\User;
use Yii;
use yii\rbac\CheckAccessInterface;
use yii\rest\ActiveController;

class UserController extends \yii\rest\ActiveController implements CheckAccessInterface {
    public $modelClass = 'app\models\User';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        return $actions;
    }

    public function actionCreate()
    {
        $data = Yii::$app->request->post();
        if ($data['password']) {
            $data['password'] = Yii::$app->getSecurity()->generatePasswordHash($data['password']);
        } else {
            unset($data['password']);
        }
        $data['sign_up_token'] = Yii::$app->security->generateRandomString( 6 );
        $data['active'] = 1;

        $user = new User();
        $user->attributes = $data;
        if ($user->save()) {
            return $user;
        }
    }

    public function actionUpdate()
    {
        $data = Yii::$app->request->post();
        if ($data['password']) {
            $data['password'] = Yii::$app->getSecurity()->generatePasswordHash($data['password']);
        } else {
            unset($data['password']);
        }

        $user = User::findOne(['id' => $data['id']]);
        $user->attributes = $data;
        if ($user->save()) {
            return $user;
        } else {
            var_dump($user->errors);
        }
    }

}


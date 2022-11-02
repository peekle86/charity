<?php


namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\rbac\CheckAccessInterface;
use yii\rest\ActiveController;

class CategoryController extends \yii\rest\ActiveController implements CheckAccessInterface {
    public $modelClass = 'app\models\Category';


//    /**
//     * {@inheritdoc}
//     */
//    public function behaviors() {
//        return [
//            'access' => [
//                'class'        => AccessControl::className(),
//                'rules'        => [
//                    [ 'allow' => true, 'actions' => [ 'signin', 'signup', 'confirm', 'restore', 'restoreconfirm', 'change' ], 'roles' => [ '@' ] ],
//                ],
//                'denyCallback' => function ( $rule, $action ) {
//                    Yii::$app->response->redirect( [ 'admin/login' ] );
//                },
//            ],
//        ];
//    }



}


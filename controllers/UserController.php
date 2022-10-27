<?php


namespace app\controllers;

use yii\rbac\CheckAccessInterface;
use yii\rest\ActiveController;

class UserController extends \yii\rest\ActiveController implements CheckAccessInterface {
    public $modelClass = 'app\models\User';
}


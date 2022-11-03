<?php


namespace app\controllers;

use yii\base\DynamicModel;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\rbac\CheckAccessInterface;
use yii\rest\ActiveController;

class LinkController extends \yii\rest\ActiveController implements CheckAccessInterface {
    public $modelClass = 'app\models\Link';

    public function actions()
    {
        $actions = parent::actions();

        $actions['index'] = [

            'class' => 'yii\rest\IndexAction',

            'modelClass' => $this->modelClass,

            'prepareDataProvider' => function () {

                return new ActiveDataProvider([

                    'query' => $this->modelClass::find(),

                    'pagination' => false,

                ]);

            },

        ];

        return $actions;
    }
}


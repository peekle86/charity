<?php


namespace app\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\rbac\CheckAccessInterface;
use yii\rest\ActiveController;

class LinkController extends \yii\rest\ActiveController implements CheckAccessInterface {
    public $modelClass = 'app\models\Link';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function actions()
    {
        $actions = parent::actions();

        $actions['index'] = [

            'class' => 'yii\rest\IndexAction',

            'modelClass' => $this->modelClass,

            'prepareDataProvider' => function () {

                $q = Yii::$app->request->get('q');
                if ($q) {
                    $query = $this->modelClass::find()
                        ->where(['like', 'title', $q]);
                } else {
                    $query = $this->modelClass::find();
                }

                return new ActiveDataProvider([
                    'query' => $query,

                ]);

            },

        ];

        return $actions;
    }
}


<?php

namespace app\controllers;

use yii\base\DynamicModel;
use yii\data\ActiveDataFilter;
use yii\rbac\CheckAccessInterface;
use yii\rest\ActiveController;

class ImportLogController extends ActiveController implements CheckAccessInterface
{
    public $modelClass = 'app\models\ImportLog';

    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['dataFilter'] = [
            'class' => ActiveDataFilter::class,
        ];

        return $actions;
    }

}
<?php


namespace app\controllers;

use app\models\Organization;
use yii\base\DynamicModel;
use yii\data\ActiveDataFilter;
use yii\helpers\Json;
use yii\rbac\CheckAccessInterface;
use yii\rest\ActiveController;

class OrganizationController extends \yii\rest\ActiveController implements CheckAccessInterface {
    public $modelClass = 'app\models\Organization';

    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['dataFilter'] = [
            'class' => ActiveDataFilter::class,
            'searchModel' => (new DynamicModel(['category_id']))->addRule(['category_id'], 'integer', ['min' => 1]),
        ];

        return $actions;
    }

    public function actionSort()
    {
        $order = $this->request->post();
        foreach ($order as $i => $id) {
            $organization = Organization::findOne(['id' => $id]);
            if ($organization) {
                $organization->sort = $i;
                $organization->save();
            }
        }
    }

}


<?php

namespace app\controllers;

use app\models\API\Adgoal;
use app\models\API\Admitad;
use app\models\Link;
use app\models\Partner;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PartnerController extends ActiveController
{
    public $modelClass = 'app\models\Partner';

    public $enableCsrfValidation = false;

    public function actionGetCustom()
    {
        $partners = Partner::find()->all();
        $result = [];

        foreach ($partners as $partner) {
            $result[] = [
                'value' => $partner->id,
                'text' => $partner->title
            ];
        }

        return $this->asJson($result);
    }

    public function actionImport()
    {
        $partner_id = $this->request->post('partner_id');
        $count = $this->request->post('count');
        $offset = $this->request->post('offset');

        if ($partner_id == Link::PARTNER_ADGOAL) {
            $importer = new Adgoal();
        } elseif ($partner_id == Link::PARTNER_ADMITAD) {
            $importer = new Admitad();
        } else {
            throw new NotFoundHttpException('Partner not found');
        }

        $importer->collect($count, $offset);

        return $this->asJson([
            'added' => $importer->added_count,
            'exists' => $importer->exists_count
        ]);
    }

}
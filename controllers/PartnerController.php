<?php

namespace app\controllers;

use app\models\API\Adgoal;
use app\models\Link;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PartnerController extends Controller
{

    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'import' => ['post'],
                ],
            ],
        ];
    }

    public function actionGet()
    {
        $partners = Link::ALL_PARTNERS;
        $result = [];

        foreach ($partners as $id => $name) {
            $result[] = [
                'value' => $id,
                'text' => $name
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
            $importer = null;
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
<?php

namespace app\controllers;

use app\models\API\Adgoal;
use app\models\API\Admitad;
use app\models\ImportLog;
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

    public function actionGetImporters()
    {
        $partners = Link::ALL_PARTNERS;
        $result = [];
        $result[] = [
            'value' => 'all',
            'text' => 'All partners'
        ];

        foreach ($partners as $id => $partner) {
            $result[] = [
                'value' => $id,
                'text' => $partner
            ];
        }

        return $this->asJson($result);
    }

    public function actionImport()
    {
        $partner_id = $this->request->post('partner_id');
        $log = new ImportLog();
        $log->status = 'In process';
        $log->save();

        if ($partner_id == Link::PARTNER_ADGOAL) {
            $importer = new Adgoal();
            $partners = 'Adgoal';
        } elseif ($partner_id == Link::PARTNER_ADMITAD) {
            $importer = new Admitad();
            $partners = 'Admitad';
        } else {
            $importer = [
                new Adgoal(),
                new Admitad()
            ];
            $partners = 'Adgoal, Admitad';
        }

        $new_merchants_count = 0;
        $new_domains_count = 0;
        $time_spent = 0;

        if (is_array($importer)) {
            foreach ($importer as $item) {
                $item->collect();
                $new_merchants_count += $item->added_count;
                $new_domains_count += $item->new_domains_count;
                $time_spent += $item->time_spent;
            }
        } else {
            $importer->collect();
            $new_merchants_count += $importer->added_count;
            $new_domains_count += $importer->new_domains_count;
            $time_spent += $importer->time_spent;
        }

        $log->partners = $partners;
        $log->new_merchants = $new_merchants_count;
        $log->new_domains = $new_domains_count;
        $log->time = date("H:i:s", $time_spent);
        $log->status = 'Done';
        $log->save();
    }

}
<?php

namespace app\commands;

use app\jobs\ImportJob;
use app\models\API\Adgoal;
use app\models\API\Admitad;
use app\models\ImportLog;
use app\models\Link;
use Yii;

class ImportController extends \yii\console\Controller
{

    public function actionIndex()
    {
        $log = new ImportLog();
        $log->status = 'In process';
        $log->partners = 'Adgoal, Admitad';
        $log->save();

        Yii::$app->queue->push(new ImportJob([
            'importer' => new Admitad(),
            'log_id' => $log->id,
        ]));
        Yii::$app->queue->push(new ImportJob([
            'importer' => new Adgoal(),
            'log_id' => $log->id,
        ]));

//        $adgoal = new Adgoal();
//        $adgoal->collect();

//        $queue = Yii::$app->queue;
//        $queue->run(false);

//        $admitad = new Admitad();
//        $admitad->collect();

//        $log->new_merchants = $adgoal->added_count + $admitad->added_count;
//        $log->new_domains = $adgoal->new_domains_count + $admitad->new_domains_count;
//        $log->time = date("H:i:s", $adgoal->spent_time + $admitad->spent_time);
//        $log->status = 'Done';
//        $log->save();
    }

    public function actionAdgoalPictures()
    {
        $adgoal = new Adgoal();
        $adgoal->setMerchantsList();
        foreach ($adgoal->merchants as $merchant) {
            $link = Link::find()->where(['adgoal_id' => $merchant['merchants_id']])->one();
            if ($link && empty($link->image)) {
                $link->image = $adgoal->getImageEncoded($merchant['logo']);
                if (!$link->save()) {
                    var_dump($link->errors);die;
                }
            } else {
                echo 'Link with "Adgoal" ID: ' . $merchant['merchants_id'] . ' не знайдено в базі';
            }
        }
    }

    public function actionAdmitadPictures()
    {
        $admitad = new Admitad();
        $admitad->setMerchantsList();
        foreach ($admitad->merchants as $merchant) {
            $link = Link::find()->where(['admitad_id' => $merchant['id']])->one();
            if ($link && empty($link->image)) {
                $link->image = $admitad->getImageEncoded($merchant['image']);
                $link->save();
            }
        }
    }

}
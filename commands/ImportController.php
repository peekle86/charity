<?php

namespace app\commands;

use app\models\API\Adgoal;
use app\models\API\Admitad;
use app\models\ImportLog;

class ImportController extends \yii\console\Controller
{

    public function actionIndex()
    {
        $log = new ImportLog();
        $log->status = 'In process';
        $log->partners = 'Adgoal, Admitad';
        $log->save();

        $adgoal = new Adgoal();
        $adgoal->collect();

        $admitad = new Admitad();
        $admitad->collect();

        $log->new_merchants = $adgoal->added_count + $admitad->added_count;
        $log->new_domains = $adgoal->new_domains_count + $admitad->new_domains_count;
        $log->time = date("H:i:s", $adgoal->spent_time + $admitad->spent_time);
        $log->status = 'Done';
        $log->save();
    }

}
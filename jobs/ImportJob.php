<?php

namespace app\jobs;

use app\models\API\Adgoal;
use app\models\API\Admitad;
use app\models\ImportLog;

/**
 * Class ImportJob.
 */
class ImportJob extends \yii\base\BaseObject implements \yii\queue\JobInterface
{
    public $importer;

    public $log_id;

    /**
     * @inheritdoc
     */
    public function execute($queue)
    {
        $this->importer->collect();
        $new_merchants_count = $this->importer->added_count;
        $new_domains_count = $this->importer->new_domains_count;
        $time_spent = $this->importer->time_spent;

        $log = ImportLog::findOne(['id' => $this->log_id]);
        $log->new_merchants = (int)$log->new_merchants + $new_merchants_count;
        $log->new_domains = (int)$log->new_domains + $new_domains_count;
        $log->time = (int)$log->time + (int)$time_spent;
        if (get_class($this->importer) == Admitad::class) {
            if ($log->partners == 'Admitad') {
                $log->status = 'Done';
            }
        } else {
            $log->status = 'Done';
        }
        $log->save();
    }
}

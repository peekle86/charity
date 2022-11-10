<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

class ImportLog extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%import_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['partners', 'status'], 'string'],
            [['new_merchants', 'time', 'new_domains'], 'integer'],
        ];
    }

    public function fields()
    {
        return [
            'partners',
            'new_merchants',
            'new_domains',
            'time' => function () {
                return date('H:i:s', $this->time);
            },
            'status',
            'cron_started' => function () {
                return Yii::$app->formatter->asDate($this->created_at, 'php:H:i:s d.m.y');
            },
            'cron_finished' => function () {
                if ($this->created_at != $this->updated_at) {
                    return Yii::$app->formatter->asDate($this->updated_at, 'php:H:i:s d.m.y');
                } else {
                    return null;
                }
            }
        ];
    }

}
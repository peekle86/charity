<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%logs}}".
 *
 * @property int $id
 * @property string|null $type
 * @property string|null $data
 */
class Logs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%logs}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data'], 'string'],
            [['type'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'data' => Yii::t('app', 'Data'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return LogsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LogsQuery(get_called_class());
    }
}

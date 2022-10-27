<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cache}}".
 *
 * @property int $id
 * @property string|null $data
 * @property string|null $k
 * @property string|null $date
 */
class Cache extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cache}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data', 'date'], 'safe'],
            [['k'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'data' => Yii::t('app', 'Data'),
            'k' => Yii::t('app', 'K'),
            'date' => Yii::t('app', 'Date'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return CacheQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CacheQuery(get_called_class());
    }
}

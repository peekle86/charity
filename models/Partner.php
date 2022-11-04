<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property int $id
 * @property string|null $title
 */
class Partner extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%partner}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return PartnerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PartnerQuery(get_called_class());
    }
}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%lang}}".
 *
 * @property int $id
 * @property string|null $lang_code
 * @property string|null $lang_name
 * @property int|null $default
 * @property int|null $active
 */
class Lang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lang}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['default', 'active'], 'integer'],
            [['lang_code', 'lang_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'lang_code' => Yii::t('app', 'Lang Code'),
            'lang_name' => Yii::t('app', 'Lang Name'),
            'default' => Yii::t('app', 'Default'),
            'active' => Yii::t('app', 'Active'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return LangQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LangQuery(get_called_class());
    }
}

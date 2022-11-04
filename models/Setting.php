<?php

namespace app\models;

use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%setting}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string $value
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%setting}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['value'], 'safe'],
            [['name'], 'string', 'max' => 55],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'value' => Yii::t('app', 'Value'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return SettingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SettingQuery(get_called_class());
    }

    public static function fastSetValue(string $name, $value)
    {
        $setting = self::findOne(['name' => $name]);
        if (!$setting) {
            throw new NotFoundHttpException('Setting not found');
        }

        $setting->value = $value;
        return $setting->save();
    }

}

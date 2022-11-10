<?php

namespace app\models;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $public_key
 * @property string|null $secret_key
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
            [['title', 'public_key', 'secret_key'], 'string'],
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

    public static function getPublicKey(int $id)
    {
        $partner = self::findOne(['id' => $id]);
        if (!$partner) {
            throw new NotFoundHttpException('Cant found partner');
        }

        if (!$partner->public_key || empty($partner->public_key)) {
            throw new NotFoundHttpException('Cant found public key');
        }

        return $partner->public_key;
    }

    public static function getPrivateKey(int $id)
    {
        $partner = self::findOne(['id' => $id]);
        if (!$partner) {
            throw new NotFoundHttpException('Cant found partner');
        }

        if (!$partner->private_key || empty($partner->private_key)) {
            throw new NotFoundHttpException('Cant found private key');
        }

        return $partner->private_key;
    }

}

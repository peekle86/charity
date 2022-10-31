<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%link}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $title
 * @property string|null $text
 * @property string|null $call_to_action
 * @property string|null $target_domain
 * @property string|null $url
 * @property string|null $place
 * @property int|null $active
 * @property string|null $image
 */
class Link extends \yii\db\ActiveRecord
{

    const PARTNER_ADGOAL = 1;
    const PARTNER_ADMITAD = 2;

    const ALL_PARTNERS = [
        self::PARTNER_ADGOAL => 'Adgoal',
        self::PARTNER_ADMITAD => 'Admitad'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%link}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'title', 'text', 'call_to_action', 'target_domain', 'url', 'image'], 'string'],
            [['place'], 'safe'],
            [['active'], 'integer'],
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
            'title' => Yii::t('app', 'Title'),
            'text' => Yii::t('app', 'Text'),
            'call_to_action' => Yii::t('app', 'Call To Action'),
            'target_domain' => Yii::t('app', 'Call To Action'),
            'url' => Yii::t('app', 'Url'),
            'place' => Yii::t('app', 'Place'),
            'active' => Yii::t('app', 'Active'),
            'image' => Yii::t('app', 'Image'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return LinkQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LinkQuery(get_called_class());
    }

    public function getDomains()
    {
        return $this->hasMany(LinkDomain::class, ['link_id' => 'id']);
    }

    public function getActiveDomain()
    {
        return $this->getDomains()->where(['active' => 1])->limit(1)->one();
    }

    public function hasDomain($domain)
    {
        return (bool) $this->getDomains()->where(['name' => $domain])->count();
    }

}

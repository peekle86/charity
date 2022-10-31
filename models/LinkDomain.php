<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%link_domain}}".
 *
 * @property int $id
 * @property int|null $partner_id
 * @property int|null $link_id
 * @property string|null $slug
 * @property string|null $name
 * @property string|null $affiliate_url
 * @property boolean $active
 */
class LinkDomain extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%link_domain}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'affiliate_url', 'name'], 'string'],
            [['partner_id'], 'integer'],
            [['partner_id'], 'in', 'range' => array_keys(Link::ALL_PARTNERS)],
            [['link_id'], 'exist', 'skipOnEmpty' => false, 'targetClass' => Link::class, 'targetAttribute' => ['link_id' => 'id']],
            [['active'], 'boolean'],
            [['active'], 'default', 'value' => 0],
        ];
    }

    public function getLink()
    {
        return $this->hasOne(Link::class, ['id' => 'link_id']);
    }

}
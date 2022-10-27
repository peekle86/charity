<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Cache]].
 *
 * @see Cache
 */
class CacheQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Cache[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Cache|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

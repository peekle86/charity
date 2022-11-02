<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%organization}}`.
 */
class m221102_103338_add_sort_column_to_organization_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'organization',
            'sort',
            $this->integer()->defaultValue(9999));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}

<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%organization}}`.
 */
class m221107_103015_add_page_columns_to_organization_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('organization', 'slug', $this->string()->after('id'));
        $this->addColumn('organization', 'title', $this->string());
        $this->addColumn('organization', 'content', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}

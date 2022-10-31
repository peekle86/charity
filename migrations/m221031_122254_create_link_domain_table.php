<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%link_domain}}`.
 */
class m221031_122254_create_link_domain_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%link_domain}}', [
            'id' => $this->primaryKey(),
            'partner_id' => $this->integer()->null(),
            'link_id' => $this->integer()->null(),
            'slug' => $this->string()->null(),
            'name' => $this->string()->null(),
            'affiliate_url' => $this->text()->null(),
            'active' => $this->boolean()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%link_domain}}');
    }
}

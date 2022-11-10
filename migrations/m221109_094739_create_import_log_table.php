<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%import_log}}`.
 */
class m221109_094739_create_import_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%import_log}}', [
            'id' => $this->primaryKey(),
            'status' => $this->string(),
            'partners' => $this->string(),
            'new_merchants' => $this->integer()->defaultValue(0),
            'new_domains' => $this->integer()->defaultValue(0),
            'time' => $this->integer()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%import_log}}');
    }
}

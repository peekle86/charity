<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%partner}}`.
 */
class m221110_160415_add_id_secret_columns_to_partner_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('partner', 'public_key', $this->string());
        $this->addColumn('partner', 'secret_key', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}

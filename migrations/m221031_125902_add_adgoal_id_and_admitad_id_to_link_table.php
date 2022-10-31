<?php

use yii\db\Migration;

/**
 * Class m221031_125902_add_adgoal_id_and_admitad_id_to_link_table
 */
class m221031_125902_add_adgoal_id_and_admitad_id_to_link_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('link', 'adgoal_id', $this->bigInteger()->null());
        $this->addColumn('link', 'admitad_id', $this->bigInteger()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221031_125902_add_adgoal_id_and_admitad_id_to_link_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221031_125902_add_adgoal_id_and_admitad_id_to_link_table cannot be reverted.\n";

        return false;
    }
    */
}

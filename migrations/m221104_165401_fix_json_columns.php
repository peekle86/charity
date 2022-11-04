<?php

use yii\db\Migration;

/**
 * Class m221104_165401_fix_json_columns
 */
class m221104_165401_fix_json_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('link', 'place');
        $this->addColumn('link', 'place', $this->text()->after('target_domain')->null());

        $this->dropColumn('page', 'seo');
        $this->addColumn('page', 'seo', $this->text()->after('content')->null());

        $this->dropColumn('setting', 'value');
        $this->addColumn('setting', 'value', $this->text()->after('name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221104_165401_fix_json_columns cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221104_165401_fix_json_columns cannot be reverted.\n";

        return false;
    }
    */
}

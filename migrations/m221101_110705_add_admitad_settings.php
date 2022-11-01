<?php

use app\models\Setting;
use yii\db\Migration;

/**
 * Class m221101_110705_add_admitad_settings
 */
class m221101_110705_add_admitad_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $client_id = new Setting();
        $client_id->name = 'admitad_client_id';
        $client_id->value = '3mPJziZJ8UsL47XybhUQiBYq4RUIuQ';
        $client_id->save();

        $client_secret = new Setting();
        $client_secret->name = 'admitad_client_secret';
        $client_secret->value = 'SYiWyX9rMoI0oYl1zdGJJ4MFS13UWd';
        $client_secret->save();

        $admitad_access_token = new Setting();
        $admitad_access_token->name = 'admitad_access_token';
        $admitad_access_token->value = '1';
        $admitad_access_token->save();

        $admitad_access_token = new Setting();
        $admitad_access_token->name = 'admitad_refresh_token';
        $admitad_access_token->value = '1';
        $admitad_access_token->save();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221101_110705_add_admitad_settings cannot be reverted.\n";

        return false;
    }
    */
}

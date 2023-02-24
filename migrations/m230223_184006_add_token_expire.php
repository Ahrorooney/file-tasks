<?php

use yii\db\Migration;

/**
 * Class m230223_184006_add_token_expire
 */
class m230223_184006_add_token_expire extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'token_expire_datetime', $this->dateTime()->after('accessToken'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'token_expire_datetime');
    }

}

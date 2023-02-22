<?php

use yii\db\Migration;

/**
 * Class m230222_063653_addUserAndRoles
 */
class m230222_063653_addUserAndRoles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('user', [
            'username' => 'admin',
            'password' => '$2y$13$qo0cdINZODbrM5IzJF39CesZzw2nEn49mre8Is0wS/4LCrMiOJKW.',
            'authKey' => '',
            'accessToken' => '-5hoJG9Hjt5-fmxQGLlZf4fb1H23Kr7d',
        ]);

        $this->insert('user', [
            'username' => 'user',
            'password' => '$2y$13$qo0cdINZODbrM5IzJF39CesZzw2nEn49mre8Is0wS/4LCrMiOJKW.',
            'authKey' => '',
            'accessToken' => '-5hoJG9Hjt5-fmxQGLkZf4fb1H23Kv7d',
        ]);

        $this->insert('user', [
            'username' => 'user_2',
            'password' => '$2y$13$qo0cdINZODbrM5IzJF39CesZzw2nEn49mre8Is0wS/4LCrMiOJKW.',
            'authKey' => '',
            'accessToken' => '-5hoJG9Hjt5-fmxQGLkZf4fb1H23Kv7d',
        ]);

        $this->insert('user', [
            'username' => 'moderator',
            'password' => '$2y$13$qo0cdINZODbrM5IzJF39CesZzw2nEn49mre8Is0wS/4LCrMiOJKW.',
            'authKey' => '',
            'accessToken' => '-5hoJG5Djt5-fmxQGLkZf4fb1H23Kv7d',
        ]);

        $this->insert('user', [
            'username' => 'moderator_2',
            'password' => '$2y$13$qo0cdINZODbrM5IzJF39CesZzw2nEn49mre8Is0wS/4LCrMiOJKW.',
            'authKey' => '',
            'accessToken' => '-5hoJG5Djt5-fmxQGLkZf4fb1R33Kv7d',
        ]);

        $auth = Yii::$app->authManager;

        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $user_role = $auth->createRole('user');
        $auth->add($user_role);

        $moderator = $auth->createRole('moderator');
        $auth->add($moderator);

        $auth->assign($admin, 1);
        $auth->assign($user_role, 2);
        $auth->assign($user_role, 3);
        $auth->assign($moderator, 4);
        $auth->assign($moderator, 5);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230222_063653_addUserAndRoles cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230222_063653_addUserAndRoles cannot be reverted.\n";

        return false;
    }
    */
}

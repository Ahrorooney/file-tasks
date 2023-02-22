<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%files}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m230221_222325_create_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%files}}', [
            'id' => $this->primaryKey(),
            'hash_sum' => $this->string(),
            'filename' => $this->string(),
            'extension' => $this->string(),
            'user_id' => $this->integer()->notNull(),
            'file_location' => $this->string(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-files-user_id}}',
            '{{%files}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-files-user_id}}',
            '{{%files}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-files-user_id}}',
            '{{%files}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-files-user_id}}',
            '{{%files}}'
        );

        $this->dropTable('{{%files}}');
    }
}

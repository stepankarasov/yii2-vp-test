<?php

use yii\db\Migration;

/**
 * Class m200320_043119_transaction_table
 */
class m200320_043119_transaction_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%transaction}}', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'amount' => $this->double(2)->notNull(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            "fk-transaction-user-id",
            'transaction',
            'user_id',
            'user',
            'id'
        );
    }

    public function down()
    {
        $this->dropTable('{{%transaction}}');
    }
}

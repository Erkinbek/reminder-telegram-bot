<?php

use yii\db\Migration;

/**
 * Class m190201_115956_reminding
 */
class m190201_115956_reminding extends Migration
{
	public function safeUp()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('{{%reminding}}', [
			'id' => $this->primaryKey(),
			'tuser_id' => $this->integer()->notNull(),
			'month' => $this->integer(2)->notNull(),
			'day' => $this->integer(2)->notNull(),
			'comment' => $this->string(255),
		], $tableOptions);

		$this->addForeignKey(
			'fk-tusersid',
			'reminding',
			'tuser_id',
			'tusers',
			'id',
			'CASCADE'
		);
	}

	public function down()
	{
		$this->dropTable('{{%reminding}}');
		return false;
	}
}

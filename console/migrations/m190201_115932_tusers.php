<?php

use yii\db\Migration;

/**
 * Class m190201_115932_tusers
 */
class m190201_115932_tusers extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('{{%tusers}}', [
			'id' => $this->primaryKey(),
			'name' => $this->string(255)->notNull(),
			'username' => $this->string(55),
			'chat_id' => $this->integer()->notNull()->unique(),
		], $tableOptions);
	}
	public function down()
	{
		$this->dropTable('{{%tusers}}');
		return false;
	}

}

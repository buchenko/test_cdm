<?php


class m160316_183925_create_movie_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('movie', array(
			'id' => 'INTEGER PRIMARY KEY',
			'title' => 'TEXT',
			'original_title' => 'TEXT',
			'release_date' => 'TEXT',
			'runtime' => 'TEXT',
			'overview' => 'TEXT',
			'genres' => 'TEXT',
			'poster_path' => 'TEXT',
		));
	}

	public function down()
	{
		$this->dropTable('movie');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
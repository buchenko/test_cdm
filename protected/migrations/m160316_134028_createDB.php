<?php

class m160316_134028_createDB extends CDbMigration
{
	public function up()
	{
		$fileNameDB = Yii::getPathOfAlias('application').'/data/themovie.db';
		if(!file_exists($fileNameDB)){
			fopen ( $fileNameDB, 'w+' );
		}

	}

	public function down()
	{
		echo "m160316_134028_createDB does not support migration down.\n";
		return false;
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
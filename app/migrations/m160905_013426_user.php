<?php

use yii\db\Migration;

class m160905_013426_user extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(32)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(),
            'email' => $this->string()->notNull(),

            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            ], $tableOptions);

        $this->createTable('{{%user_profile}}', [
            'user_id' => $this->integer(),
            'fullname' => $this->string()->notNull(),
            'photo_id'=> $this->integer(),
            'avatar' => $this->string(),
            'gender' => $this->string(),
            'address' => $this->string(255),
            'birth_day' => $this->date(),
            'bio' => $this->text(),
            'contacts' => $this->binary(),

            'PRIMARY KEY ([[user_id]])',
            'FOREIGN KEY ([[user_id]]) REFERENCES {{%user}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%auth}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string(128),
            'source_id' => $this->string(128),
            
            'FOREIGN KEY ([[user_id]]) REFERENCES {{%user}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%token}}', [
            'id' => $this->string(32),
            'category' => $this->string(32),
            'expire' => $this->integer(),
            'data' => $this->binary(),
            // constraint
            'PRIMARY KEY ([[id]])',
        ], $tableOptions);

        $this->createTable('{{%client}}', [
            'id' => $this->primaryKey(),
            'fcm_key' => $this->string(),
            'user_id' => $this->integer(),
            'data' => $this->binary(),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%client}}');
        $this->dropTable('{{%token}}');
        $this->dropTable('{{%auth}}');
        $this->dropTable('{{%user_profile}}');
        $this->dropTable('{{%user}}');
    }
}
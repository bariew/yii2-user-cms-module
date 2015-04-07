<?php

use yii\db\Schema;
use yii\db\Migration;

class m150407_062517_user_auth extends Migration
{
    public function up()
    {
        return $this->createTable('user_auth', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER,
            'name' => Schema::TYPE_SMALLINT,
            'service_id' => Schema::TYPE_STRING,
            'created_at' => Schema::TYPE_INTEGER,
            'data' => Schema::TYPE_TEXT
        ]);
    }

    public function down()
    {
        return $this->dropTable('user_auth');
    }
}

<?php

class m140417_081329_user_user extends \yii\db\Migration
{
    public function up()
    {
        $this->createTable('user_user', array(
            'id'            => 'pk',
            'email'         => 'string',
            'password'      => 'string',
            'auth_key'      => 'string',
            'api_key'       => 'string',
            'username'      => 'string',
            'company_name'  => 'string',
            'status'        => 'integer',
            'created_at'    => 'integer',
            'updated_at'    => 'integer',
            'password_reset_token'=>'string',
        ));

        $this->insert('user_user', array(
            'email'         => 'admin@admin.admin',
            'password'      => 'e0afae8445b14dc6fc3b31116f113ccc544da8a9aba8c37a5dd2b4ee95c21d6c',
            'username'      => 'pt',
            'company_name'  => 'pt',
            'status'        => 10,
            'created_at'    => time(),
        ));
    }

    public function down()
    {
        return $this->dropTable('user_user');
    }
}

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
            'password'      => '$2y$13$Rx7MzVUFuYsrKAE4pUksBO2r7fecmboV4MM8WZrSCPDFI3LiHSGOm',
            'username'      => 'admin',
            'company_name'  => 'admin',
            'status'        => 10,
            'created_at'    => time(),
        ));
    }

    public function down()
    {
        return $this->dropTable('user_user');
    }
}

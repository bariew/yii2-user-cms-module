<?php

namespace bariew\userModule;
use Yii;
use bariew\userModule\models\User;
use yii\web\Application;

class Module extends \yii\base\Module
{
    public $params = [
        'emailConfirm' => false,
        'resetTokenExpireSeconds' => 86400 // one day
    ];


    public function init()
    {
        $this->params['menu'] = (!\bariew\userModule\Module::hasUser())
            ? [
                  'label'    => 'Login', 
                  'url' => ['/user/default/login']
              ]
            : [
                  'label'    => Yii::$app->user->identity->username,
                  'items' => [
                      ['label'    => 'Profile', 'url' => ['/user/default/update']],
                      ['label'    => 'Logout', 'url' => ['/user/default/logout']],
                      ['label'    => 'All users', 'url' => ['/user/user/index']]
                  ]
              ];
        parent::init();
    }

    /**
     * We just check whether module is installed and user is logged in.
     * @return bool
     */
    public static function hasUser()
    {

        if (!(Yii::$app instanceof Application)) {
            return false;
        }
        if (!Yii::$app->db->getTableSchema(User::tableName())) {
            return false;
        }
        try {
            $identityClass = Yii::$app->user->identityClass;
        } catch (\Exception $e) {
            $identityClass = false;
        }

        if (!$identityClass) {
            return false;
        }

        return !Yii::$app->user->isGuest;
    }

    public function install()
    {
        \app\config\ConfigManager::set(['components', 'user', 'identityClass'], User::className());
    }

    public function uninstall()
    {
        \app\config\ConfigManager::set(['components', 'user', 'identityClass'], '');
    }
}

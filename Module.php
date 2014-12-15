<?php

namespace bariew\userModule;
use app\config\ConfigManager;
use Yii;
use bariew\userModule\models\User;
use yii\web\Application;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'bariew\userModule\controllers';

    public $params = [];


    public function init()
    {
        $this->params = [
            'menu'  => (!\bariew\userModule\Module::hasUser())
                ? ['label'    => 'Login', 'url' => ['/user/default/login']]
                : [
                    'label'    => Yii::$app->user->identity->username,
                    'items' => [
                        ['label'    => 'Profile', 'url' => ['/user/default/update']],
                        ['label'    => 'Logout', 'url' => ['/user/default/logout']],
                        ['label'    => 'All users', 'url' => ['/user/user/index']]
                    ]
                ],
            'emailConfirm' => false,
            'resetTokenExpireSeconds' => 24*60*60
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
        ConfigManager::set(['components', 'user', 'identityClass'], User::className());
    }

    public function uninstall()
    {
        ConfigManager::set(['components', 'user', 'identityClass'], '');
    }
}

<?php

namespace bariew\userModule;
use Yii;
use bariew\userModule\models\User;
use yii\web\Application;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'bariew\userModule\controllers';

    public function getParams()
    {
        return require_once __DIR__ . DIRECTORY_SEPARATOR . 'params.php';
    }

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public static function hasUser()
    {

        if (!(Yii::$app instanceof Application)) {
            return false;
        }

        if (!Yii::$app->db->isActive) {
           // return false;
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
}

<?php
/**
 * UserBootstrap class file
 * @copyright Copyright (c) 2014 Galament
 * @license http://www.yiiframework.com/license/
 */

namespace bariew\userModule;

use Yii;
use yii\base\BootstrapInterface;
use yii\web\Application;

/**
 * Bootstrap class initiates external modules.
 * 
 * @author Pavel Bariev <bariew@yandex.ru>
 */
class UserBootstrap implements BootstrapInterface
{
    public static $userIdentityClass = 'bariew\userModule\models\User';

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if (!$app instanceof Application) {
            return true;
        }
        self::setUser();
        return true;
    }


    public static function getUser()
    {
        self::setUser();
        return Yii::$app->user;
    }

    public static function setUser()
    {
        Yii::$app->setComponents([
            'user'  => [
                'class'         => 'yii\web\User',
                'identityClass' => self::$userIdentityClass
            ],
        ]);
    }
}
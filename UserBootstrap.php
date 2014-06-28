<?php
/**
 * UserBootstrap class file
 * @copyright Copyright (c) 2014 Galament
 * @license http://www.yiiframework.com/license/
 */

namespace bariew\userModule;

use yii\base\BootstrapInterface;
use yii\base\Application;

/**
 * Bootstrap class initiates external modules.
 * 
 * @author Pavel Bariev <bariew@yandex.ru>
 */
class UserBootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        \Yii::configure($app, [
            'user'  => [
                'identityClass' => 'bariew\userModule\models\User',
                'enableAutoLogin'   => true,
            ],
        ]);
        
        return true;
    }
}
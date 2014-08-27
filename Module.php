<?php

namespace bariew\userModule;

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

}

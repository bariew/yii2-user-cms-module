<?php
Yii::setAlias('@bariew/userModule', __DIR__.'/../');
$localPath = __DIR__ . DIRECTORY_SEPARATOR . 'params-local.php';
return array_merge([
    'menu'  => (!\bariew\userModule\UserBootstrap::hasUser())
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
], (file_exists($localPath) ? require $localPath : []));
<?php
Yii::setAlias('@bariew/userModule', __DIR__.'/../');

return [
    'menu'  => (!(Yii::$app instanceof \yii\web\Application) || \bariew\userModule\UserBootstrap::getUser()->isGuest)
        ? ['label'    => 'Login', 'url' => ['/user/default/login']]
        : [
            'label'    => Yii::$app->user->identity->username,
            'items' => [
                ['label'    => 'Profile', 'url' => ['/user/default/update']],
                ['label'    => 'Logout', 'url' => ['/user/default/logout']],
                ['label'    => 'All users', 'url' => ['/user/user/index']]
            ]
        ]
];
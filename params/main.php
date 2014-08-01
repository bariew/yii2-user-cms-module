<?php

return [
    'menu'  => (Yii::$app->user->isGuest)
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
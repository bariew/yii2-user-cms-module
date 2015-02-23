
Yii2 user module.
===================
Standard yii2 module for user management.

Installation
------------

1. Mention module class in app config
```
'modules' => [
    ...
    'user' => [
        'class' => 'bariew\userModule\Module',
        'params' => [
            'emailConfirm' => false, // whether registration email confirm is required
            'resetTokenExpireSeconds' => 86400 // seconds till password reset token expires
        ]
    ]
]
```

2. Set app user component identity class.
```
'components' => [
    ...
    'user' => [
        'identityClass' => 'bariew\userModule\models\User',
    ]
]
```
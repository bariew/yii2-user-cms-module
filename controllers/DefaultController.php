<?php

namespace bariew\userModule\controllers;

use bariew\userModule\models\LoginForm;
use bariew\userModule\models\RegisterForm;
use yii\web\Controller;
use bariew\userModule\models\User;
use Yii;

class DefaultController extends Controller
{
    public $modelClass = 'User';
    
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionRegister()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash("success", Yii::$app->user->isGuest
                ? Yii::t('modules/user', 'Please confirm registration email!')
                : Yii::t('modules/user', 'Registration completed!')
            );
            return $this->goHome();
        }
        return $this->render('register', compact('model'));
    }
    
    public function actionConfirm($auth_key)
    {
        if ($auth_key && $user = User::findOne(compact('auth_key'))) {
            Yii::$app->session->setFlash("success", Yii::t('modules/user', "You have successfully completed your registration."));
            Yii::$app->user->login($user);
            $user->activate();
        }else{
            Yii::$app->session->setFlash("danger", Yii::t('modules/user', "Your auth link is invalid."));
        }
        return $this->goHome();
    }
    
    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpdate()
    {
        if (!$model = Yii::$app->user->identity) {
            Yii::$app->session->setFlash("danger", Yii::t('modules/user', "You are not logged in."));
            $this->goHome();
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash("success", Yii::t('modules/user', "Changes has been saved."));
            return $this->refresh();
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    /**
     * @example $this->get("http://blacklist.dev/user/default/test") == '{"code":200,"message":"OK"}'
     */
    public function actionTest()
    {
        echo json_encode(['code'=>200, 'message'=>'OK']);
    }
}

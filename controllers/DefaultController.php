<?php
/**
 * DefaultController class file.
 * @copyright (c) 2015, Pavel Bariev
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace bariew\userModule\controllers;

use bariew\userModule\models\LoginForm;
use bariew\userModule\models\RegisterForm;
use yii\web\Controller;
use bariew\userModule\models\User;
use Yii;
 
/**
 * Default controller for all users.
 * 
 * 
 * @author Pavel Bariev <bariew@yandex.ru>
 */
class DefaultController extends Controller
{
    /**
     * Renders login form.
     * @return string view.
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        
        return $this->render('login', compact('model'));
    }

    /**
     * Logs user out and redirects to homepage.
     * @return string view.
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    /**
     * Registers user.
     * @return string view.
     */
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
    
    /**
     * For registration confirmation by email auth link.
     * @param string $auth_key user authorization key.
     * @return string view.
     */
    public function actionConfirm($auth_key)
    {
        if ($auth_key && $user = User::findOne(compact('auth_key'))) {
            Yii::$app->session->setFlash("success", Yii::t('modules/user', "You have successfully completed your registration."));
            Yii::$app->user->login($user);
            $user->activate();
        }else{
            Yii::$app->session->setFlash("error", Yii::t('modules/user', "Your auth link is invalid."));
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
            Yii::$app->session->setFlash("error", Yii::t('modules/user', "You are not logged in."));
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
}
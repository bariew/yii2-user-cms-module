<?php
/**
 * DefaultController class file.
 * @copyright (c) 2015, Pavel Bariev
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace bariew\userModule\controllers;

use bariew\userModule\models\Auth;
use bariew\userModule\models\LoginForm;
use bariew\userModule\models\RegisterForm;
use yii\authclient\AuthAction;
use yii\web\Controller;
use bariew\userModule\models\User;
use yii\authclient\BaseOAuth;
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
     * Url for redirecting after login
     * @return null
     */
    public function getLoginRedirect()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::className(),
                'successCallback' => [$this, 'authCallback'],
                'successUrl' => Yii::$app->urlManager->createAbsoluteUrl(['/user/default/login'])
            ],
        ];
    }

    /**
     * @param BaseOAuth $client
     */
    public function authCallback(BaseOAuth $client)
    {
        $user = Auth::clientUser($client);
        (new LoginForm(['username' => $user->username]))->login(false);
    }

    /**
     * Renders login form.
     * @param string $view
     * @param bool $partial
     * @return string view.
     */
    public function actionLogin($view = 'login', $partial = false)
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return ($url = $this->getLoginRedirect())
                ? $this->redirect($url) 
                : $this->goBack();
        }
        if (\Yii::$app->request->isAjax || $partial) {
            return $this->renderAjax($view, compact('model'));
        }
        return $this->render($view, compact('model'));
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
            return (($url = $this->getLoginRedirect()) && !Yii::$app->user->isGuest)
                ? $this->redirect($url) 
                : $this->goBack();
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
        $model = $this->findModel(true);
        /**
         * @var User $user
         */
        if ($auth_key && ($user = $model::findOne(compact('auth_key')))) {
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
        if (!$model = $this->findModel()) {
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
    
    /**
     * Finds user model.
     * @param boolean $new
     * @return User
     */
    public function findModel($new = false)
    {
        $class = \Yii::$app->user->identityClass;
        return $new === true ? new $class() : Yii::$app->user->identity;
    }
}
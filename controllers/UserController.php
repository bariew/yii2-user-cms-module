<?php
/**
 * UserController class file.
 * @copyright (c) 2015, Pavel Bariev
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace bariew\userModule\controllers;

use Yii;
use bariew\userModule\models\User;
use bariew\userModule\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
 
/**
 * UserController implements the CRUD actions for User model.
 * 
 * 
 * @author Pavel Bariev <bariew@yandex.ru>
 */
class UserController extends Controller
{
    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = $this->findModel(null, true);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single User model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = $this->findModel(null);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Successfully saved'));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Successfully saved'));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Successfully deleted'));
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @param bool $search
     * @return User|UserSearch the loaded model
     * @throws NotFoundHttpException
     */
    protected function findModel($id, $search = false)
    {
        if ($search) {
            $model = new UserSearch();
        } elseif (!$id) {
            $model = new User();
        } elseif (!$model = User::findOne($id)) {
            throw new NotFoundHttpException(\Yii::t('modules/user', 'User not found'));
        }
        $model->scenario = 'root';
        return $model;
    }
}

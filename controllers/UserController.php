<?php

namespace app\controllers;

use app\models\Transaction;
use app\models\User;
use app\models\UserSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * Lists all User models.
     * @return mixed
     * @throws \Exception
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($params);

        $modelUser = new User();
        $modelTransaction = new Transaction();

        if ($modelUser->load(Yii::$app->request->post()) && $modelUser->save()) {
            return $this->redirect(['/users']);
        }

        if ($modelTransaction->load(Yii::$app->request->post())) {
            $user_id = Yii::$app->request->post('user_id');
            $user_phone = Yii::$app->request->post('user_phone');

            $modelUser = User::find()->where(['id'=> $user_id])->orWhere(['phone'=> $user_phone])->one();

            if ($modelUser == null) {
                throw new NotFoundHttpException('Пользователь не найден');
            }

            if ($modelUser->status == 0) {
                throw new NotFoundHttpException('Пользователь заблокирован');
            }

            if ($modelTransaction->load(Yii::$app->request->post())) {
                $modelTransaction->user_id = $modelUser->id;

                if ($modelTransaction->save()) {
                    return $this->redirect(['/users']);
                }
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'modelUser' => $modelUser,
            'modelTransaction' => $modelTransaction,
        ]);
    }

    /**
     * Creates a new Users model.
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {

                return [
                    'code'    => 200,
                    'message' => 'Пользователь зарегистрирован',
                    'data'    => $model,
                ];
            } else {
                Yii::$app->response->statusCode = 400;

                return [
                    'code'    => 400,
                    'message' => 'Ошибка при регистрации',
                    'errors'  => $model->errors,
                ];
            }
        }

        return [
            'code'    => 400,
            'message' => 'Ошибка валидации',
            'errors'  => $model,
        ];
    }

    /**
     * Update Users model.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {

            return [
                'code'    => 200,
                'message' => 'Пользователь обновлен',
                'data'    => $model,
            ];
        }

        return [
            'code'    => 422,
            'message' => 'Ошибка валидации',
            'errors'  => $model,
        ];
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемый пользователь не найден');
        }
    }
}

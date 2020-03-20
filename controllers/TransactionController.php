<?php

namespace app\controllers;

use app\models\Transaction;
use app\models\TransactionSearch;
use app\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TransactionController implements the CRUD actions for User model.
 */
class TransactionController extends Controller
{
    /**
     * Lists all User models.
     * @return mixed
     * @throws \Exception
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        $searchModel = new TransactionSearch();
        $dataProvider = $searchModel->search($params);

        $model = new Transaction();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/transactions']);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Transaction model.
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $modelTransaction = new Transaction();
        $user_id = Yii::$app->request->post('user_id');
        $user_phone = Yii::$app->request->post('user_phone');

        $modelUser = User::find()->where(['id'=> $user_id])->orWhere(['phone'=> $user_phone])->one();

        if ($modelUser == null) {
            Yii::$app->response->statusCode = 400;

            return [
                'code' => 400,
                'message' => 'Пользователь не найден',
                'errors'  => ['user' => 'Пользователь не найден'],
            ];
        }

        if ($modelUser->status == 0) {
            Yii::$app->response->statusCode = 400;

            return [
                'code' => 400,
                'message' => 'Пользователь заблокирован',
                'errors'  => ['user' => 'Пользователь заблокирован'],
            ];
        }

        if ($modelTransaction->load(Yii::$app->request->post())) {
            $modelTransaction->user_id = $modelUser->id;

            if ($modelTransaction->save()) {
                return [
                    'code'    => 200,
                    'message' => 'Баланс пополнен',
                    'data'    => $modelTransaction,
                ];
            }
        }

        return false;
    }

    /**
     * Update Transactions model.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {

            return [
                'code'    => 200,
                'message' => 'Транзакция обновлена',
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
     * Cancel Transactions model.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionCancel($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = $this->findModel($id);

        $model->status = 0;
        $model->save(true, ['status']);

        return $this->redirect('/transactions');
    }
    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param $id
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transaction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрашиваемая транзакция не найдена');
        }
    }
}

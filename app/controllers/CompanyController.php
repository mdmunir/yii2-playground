<?php

namespace app\controllers;

use Yii;
use app\models\ar\Company;
use app\models\ar\search\Company as CompanySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\classes\AuthFilter;
use yii\web\ForbiddenHttpException;
use app\classes\UploadImage;
use app\models\ar\CompanyContact;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'upload-photo' => ['post'],
                ],
            ],
            'auth' => [
                'class' => AuthFilter::className(),
                'only' => ['create', 'update', 'delete'],
            ]
        ];
    }

    /**
     * Lists all Company models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Company model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id = null)
    {
        if ($id == null) {
            if (Yii::$app->user->isGuest) {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
            } elseif (($model = Yii::$app->user->identity->company) === null) {
                return $this->redirect(['create']);
            }
        } else {
            $model = $this->findModel($id);
        }
        return $this->render('view', [
                'model' => $model,
        ]);
    }

    public function actionUploadPhoto()
    {
        /* @var $model Company */
        $model = Yii::$app->user->identity->company;
        $photo_id = UploadImage::store('image', [
                'crop' => Yii::$app->getRequest()->post('crop'),
                'rules' => ['minWidth' => 400]
        ]);
        if ($photo_id !== false) {
            $model->photo_id = $photo_id;
            $model->save();
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->identity->company) {
            return $this->redirect(['update']);
        }
        $model = new Company([
            'user_id' => Yii::$app->user->id,
        ]);

        $contacts = [];
        foreach (CompanyContact::$types as $i => $type) {
            $contacts[$i] = new CompanyContact([
                'type' => $type,
            ]);
        }
        $model->contacts = $contacts;
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $model->contacts = Yii::$app->request->post('CompanyContact', []);
            if ($model->save()) {
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }
            $transaction->rollBack();
        }
        return $this->render('create', [
                'model' => $model,
                'contacts' => $model->contacts,
        ]);
    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        /* @var $model Company */
        $model = Yii::$app->user->identity->company;

        $contacts = $model->contacts;
        foreach (CompanyContact::$types as $i => $type) {
            if (!isset($contacts[$i])) {
                $contacts[$i] = new CompanyContact([
                    'type' => $type,
                ]);
            }
        }
        $model->contacts = $contacts;
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $model->contacts = Yii::$app->request->post('CompanyContact', []);
            if ($model->save()) {
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }
            $transaction->rollBack();
        }
        return $this->render('update', [
                'model' => $model,
                'contacts' => $model->contacts,
        ]);
    }

    /**
     * Deletes an existing Company model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->user_id != Yii::$app->user->id) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

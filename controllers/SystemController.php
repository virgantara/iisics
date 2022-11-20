<?php

namespace app\controllers;

use Yii;
use app\models\System;
use app\models\SystemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * SystemController implements the CRUD actions for System model.
 */
class SystemController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all System models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SystemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $id = Yii::$app->request->post('editableKey');
            $id = json_decode($id);
            $model = System::findOne($id->sys_id);

            // store a default json response as desired by editable
            $out = json_encode(['output'=>'', 'message'=>'']);

            
            $posted = current($_POST['System']);
            // print_r($posted);exit;
            $post = ['System' => $posted];

            
            // load model like any single model validation
            if ($model->load($post)) {
            // can save model or do something before saving model
                if($model->save(false))
                {
                    $out = json_encode(['output'=>'', 'message'=>'']);
                }

                else
                {
                    $error = \app\helpers\MyHelper::logError($model);
                    $out = json_encode(['output'=>'', 'message'=>'Oops, '.$error]);   
                }

                
            }
            // return ajax json encoded response and exit
            echo $out;
            exit;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single System model.
     * @param integer $sys_id
     * @param string $sys_name
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($sys_id, $sys_name)
    {
        return $this->render('view', [
            'model' => $this->findModel($sys_id, $sys_name),
        ]);
    }

    /**
     * Creates a new System model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new System();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data saved");
            return $this->redirect(['view', 'sys_id' => $model->sys_id, 'sys_name' => $model->sys_name]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing System model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $sys_id
     * @param string $sys_name
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($sys_id, $sys_name)
    {
        $model = $this->findModel($sys_id, $sys_name);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data saved");
            return $this->redirect(['view', 'sys_id' => $model->sys_id, 'sys_name' => $model->sys_name]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing System model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $sys_id
     * @param string $sys_name
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($sys_id, $sys_name)
    {
        $this->findModel($sys_id, $sys_name)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the System model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $sys_id
     * @param string $sys_name
     * @return System the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($sys_id, $sys_name)
    {
        if (($model = System::findOne(['sys_id' => $sys_id, 'sys_name' => $sys_name])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

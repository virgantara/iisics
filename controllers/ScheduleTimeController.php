<?php

namespace app\controllers;

use Yii;
use app\models\ScheduleTime;
use app\models\ScheduleTimeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ScheduleTimeController implements the CRUD actions for ScheduleTime model.
 */
class ScheduleTimeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException('You are not allowed to access this page');
                },
                'only' => ['create','update','delete','index','delete-multiple'],
                'rules' => [
                    
                    [
                        'actions' => [
                            'create','update','index','delete','delete-multiple'
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => [
                            'create','update','delete','index','delete-multiple'
                        ],
                        'allow' => true,
                        'roles' => ['theCreator'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionDeleteMultiple()
    {
        $errors = '';
        $results = [];
        if(!empty($_POST['dataPost'])){


            $dataPost = $_POST['dataPost'];
            
            if(!empty($dataPost['keys']))
            {
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                    $counter = 0;

                    foreach($dataPost['keys'] as $id) {
                        $model = ScheduleTime::findOne($id);
                        
                        if(!empty($model)) {
                            $counter++;
                            $model->delete();

                        }
                    }

                    $transaction->commit();

                    $results = [
                        'code' => 200,
                        'message' => 'Data successfully delete. Total: '.$counter
                    ];
                } catch (\Exception $e) {
                    $errors .= $e->getMessage();
                    $transaction->rollBack();
                    $results = [
                        'code' => 500,
                        'message' => $errors
                    ];
                }
            }
        }
        echo json_encode($results);
        die();
    }

    /**
     * Lists all ScheduleTime models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ScheduleTimeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ScheduleTime model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ScheduleTime model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ScheduleTime();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data saved");
            return $this->redirect(['schedule-day/view', 'id' => $model->day_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ScheduleTime model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data saved");
            return $this->redirect(['schedule-day/view', 'id' => $model->day_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ScheduleTime model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', "Data successfully deleted");
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the ScheduleTime model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScheduleTime the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScheduleTime::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

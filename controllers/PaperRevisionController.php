<?php

namespace app\controllers;

use Yii;
use app\models\PaperRevision;
use app\models\PaperRevisionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

use app\helpers\MyHelper;
use app\models\User;

/**
 * PaperRevisionController implements the CRUD actions for PaperRevision model.
 */
class PaperRevisionController extends Controller
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
                'only' => ['create','update','delete','index','ajax-add','download'],
                'rules' => [
                    [
                        'actions' => [
                            'create','update','delete','index','ajax-add','download'
                        ],
                        'allow' => true,
                        'roles' => ['admin','participant'],
                    ],
                    [
                        'actions' => [
                            'update'
                        ],
                        'allow' => true,
                        'roles' => ['reviewer'],
                    ],
                    [
                        'actions' => [
                            'create','update','delete','index'
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

    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        $file = $model->paper_file;
        if(empty($model->paper_file)){
            Yii::$app->session->setFlash('danger', 'Oops, this file does not exist');
            return $this->redirect(['index']);
        }
        $filename = 'FP-REV-'.$model->paper_id.'-'.$model->version.'.pdf';

        // Header content type
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
          
        // Read the file
        @readfile($file);
        exit;
    }

    /**
     * Lists all PaperRevision models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaperRevisionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PaperRevision model.
     * @param string $id
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
     * Creates a new PaperRevision model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($paper_id)
    {
        $model = new PaperRevision();
        $model->paper_id = $paper_id;
        $s3config = Yii::$app->params['s3'];
        $s3 = new \Aws\S3\S3Client($s3config);
        $paper_file = $model->paper_file;

        if ($model->load(Yii::$app->request->post())) {
            $model->id = MyHelper::gen_uuid();
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();            
            try {

                $model->paper_file = UploadedFile::getInstance($model, 'paper_file');
                if ($model->paper_file) {
                    
                    $file_name = 'REV-'.'-'.date('YmdHis').'-'.$model->id.'.'.$model->paper_file->extension;
                    $s3_path = $model->paper_file->tempName;
                    $mime_type = $model->paper_file->type;
                                    
                    $key = 'iicics/'.date('Y').'/paper-revision/'.$file_name;
                     
                    $insert = $s3->putObject([
                         'Bucket' => 'seminar',
                         'Key'    => $key,
                         'Body'   => 'This is the Body',
                         'SourceFile' => $s3_path,
                         'ContentType' => $mime_type
                    ]);

                    
                    $plainUrl = $s3->getObjectUrl('seminar', $key);
                    $model->paper_file = $plainUrl;
                   
                }
            

                if(empty($model->paper_file)){
                    $model->paper_file = $paper_file;
                }

                if($model->save()){
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', "Data saved");
                    return $this->redirect(['papers/view','id' => $model->paper_id]);
                }

                else{
                    throw new \Exception(\app\helpers\MyHelper::logError($model));
                }
            }
            catch (\Exception $e) {
                $transaction->rollBack();
                $errors .= $e->getMessage();
                Yii::$app->session->setFlash('danger', $errors);
            }

            
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PaperRevision model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data saved");
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PaperRevision model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PaperRevision model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PaperRevision the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PaperRevision::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

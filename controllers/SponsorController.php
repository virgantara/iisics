<?php

namespace app\controllers;

use Yii;
use app\models\Sponsor;
use app\models\SponsorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
/**
 * SponsorController implements the CRUD actions for Sponsor model.
 */
class SponsorController extends Controller
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
                'only' => ['create','update','delete','index'],
                'rules' => [
                    
                    [
                        'actions' => [
                            'create','update','index'
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
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

    function getImage($url){
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $resource = curl_exec($ch);
        curl_close ($ch);

        return $resource;
    }

    public function actionFoto($id){
        $model = Sponsor::findOne($id);
        if(!empty($model->file_path)){
            try{
                $image = imagecreatefromstring($this->getImage($model->file_path));

                header('Content-Type: image/png');
                imagepng($image);
            }

            catch(\Exception $e){
                   
            }
                
        }
        

        die();
    }

    /**
     * Lists all Sponsor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SponsorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sponsor model.
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
     * Creates a new Sponsor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sponsor();

        $s3config = Yii::$app->params['s3'];
        $s3 = new \Aws\S3\S3Client($s3config);
        $errors = '';
        if ($model->load(Yii::$app->request->post())) {
            
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();            
            try {

                $model->file_path = UploadedFile::getInstance($model, 'file_path');
                if ($model->file_path) {
                    
                    $file_name = 'SPONSOR-'.date('YmdHis').rand(1,100).'.'.$model->file_path->extension;
                    $s3_path = $model->file_path->tempName;
                    $mime_type = $model->file_path->type;
                                    
                    $key = 'iicics/'.date('Y').'/sponsor/'.$file_name;
                     
                    $insert = $s3->putObject([
                         'Bucket' => 'seminar',
                         'Key'    => $key,
                         'Body'   => 'This is the Body',
                         'SourceFile' => $s3_path,
                         'ContentType' => $mime_type
                    ]);

                    
                    $plainUrl = $s3->getObjectUrl('seminar', $key);
                    $model->file_path = $plainUrl;
                   
                }


                if($model->save()){

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', "Data saved");
                    return $this->redirect(['view', 'id' => $model->id]);
                }

                else{
                    throw new Exception(\app\helpers\MyHelper::logError($model));
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
     * Updates an existing Sponsor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $s3config = Yii::$app->params['s3'];
        $s3 = new \Aws\S3\S3Client($s3config);
        $file_path = $model->file_path;
        $errors = '';
        if ($model->load(Yii::$app->request->post())) {
            
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();            
            try {

                $model->file_path = UploadedFile::getInstance($model, 'file_path');
                if ($model->file_path) {
                    
                    $file_name = 'SPONSOR-'.date('YmdHis').rand(1,100).'.'.$model->file_path->extension;
                    $s3_path = $model->file_path->tempName;
                    $mime_type = $model->file_path->type;
                                    
                    $key = 'iicics/'.date('Y').'/sponsor/'.$file_name;
                     
                    $insert = $s3->putObject([
                         'Bucket' => 'seminar',
                         'Key'    => $key,
                         'Body'   => 'This is the Body',
                         'SourceFile' => $s3_path,
                         'ContentType' => $mime_type
                    ]);

                    
                    $plainUrl = $s3->getObjectUrl('seminar', $key);
                    $model->file_path = $plainUrl;
                   
                }
            

                if(empty($model->file_path)){
                    $model->file_path = $file_path;
                }

                if($model->save()){

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', "Data saved");
                    return $this->redirect(['view', 'id' => $model->id]);
                }

                else{
                    throw new Exception(\app\helpers\MyHelper::logError($model));
                }
            }
            catch (\Exception $e) {
                $transaction->rollBack();
                $errors .= $e->getMessage();
                Yii::$app->session->setFlash('danger', $errors);
            }

            
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Sponsor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Sponsor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sponsor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sponsor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

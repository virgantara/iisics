<?php

namespace app\controllers;

use Yii;

use app\models\Abstracts;

use app\models\Papers;
use app\models\PapersSearch;
use app\models\PaperReview;
use app\models\PaperReviewSearch;
use app\models\Payment;
use app\models\System;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;


/**
 * PapersController implements the CRUD actions for Papers model.
 */
class PapersController extends Controller
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
                'only' => ['create','update','delete','index','download','my-review','ajax-change'],
                'rules' => [
                    [
                        'actions' => [
                            'create','update','index','download'
                        ],
                        'allow' => true,
                        'roles' => ['participant'],
                    ],
                    [
                        'actions' => [
                            'view','index','download','my-review'
                        ],
                        'allow' => true,
                        'roles' => ['reviewer'],
                    ],
                    [
                        'actions' => [
                            'update','delete','index'
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => [
                            'create','update','delete','index','ajax-change'
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

    public function actionAjaxChange()
    {
        $results = [];

        $errors = '';
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        

        try 
        {
            $dataPost = $_POST;

            $model = Papers::findOne($dataPost['paper_id']);

            if(!empty($model)){
                $model->paper_status = $dataPost['paper_status'];    
                if($model->save())
                {

                    $email = $model->p->email;
                    $emailTemplate = '';
                    if($model->paper_status == 'Accepted'){

                        $emailTemplate = $this->renderPartial('email_accept',[
                            'model' => $model,
                        ]);
                    }

                    else if($model->paper_status == 'Rejected'){
                        $emailTemplate = $this->renderPartial('email_reject',[
                            'model' => $model,
                        ]);
                    }
                    Yii::$app->mailer->compose()
                    ->setTo($email)
                    ->setFrom([Yii::$app->params['supportEmail'] => 'SNST Notification'])
                    ->setSubject('[SNST] Fullpaper Announcement')
                    ->setHtmlBody($emailTemplate)
                    ->send();
                    $transaction->commit();
                    $results = [
                        'code' => 200,
                        'message' => 'Data successfully updated'
                    ]; 
                    
                }

                else
                {
                    $errors .= MyHelper::logError($model);
                    throw new \Exception;
                }

            }

            
        } 

        catch (\Exception $e) {
            $transaction->rollBack();
            $errors .= $e->getMessage();
            $results = [
                'code' => 500,
                'message' => $errors
            ];
            
            
        } catch (\Throwable $e) {
            $transaction->rollBack();
            $errors .= $e->getMessage();
            $results = [
                'code' => 500,
                'message' => $errors
            ];
            
        }
        echo json_encode($results);
        exit;
    }

    public function actionMyReview()
    {
        $searchModel = new PaperReviewSearch();
        $dataProvider = $searchModel->searchMyReview(Yii::$app->request->queryParams);

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $modelAbs = PaperReview::findOne($id);

            $out = json_encode(['output'=>'', 'message'=>'']);

            
            $posted = current($_POST['PaperReview']);
            $post = ['PaperReview' => $posted];

            
            if ($modelAbs->load($post)) {
                if($modelAbs->save())
                {
                    $out = json_encode(['output'=>'', 'message'=>'']);
                }

                else
                {
                    $error = \app\helpers\MyHelper::logError($modelAbs);
                    $out = json_encode(['output'=>'', 'message'=>'Oops, '.$error]);   
                }

                
            }
            echo $out;
            exit;
        }

        return $this->render('my_review', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        $file = $model->paper_file;
        if(empty($model->paper_file)){
            Yii::$app->session->setFlash('danger', 'Oops, this file does not exist');
            return $this->redirect(['index']);
        }
        $filename = 'PID'.$model->paper_id.'.pdf';

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
     * Lists all Papers models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
        }
        
        $searchModel = new PapersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Papers model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
        }

        $model = $this->findModel($id);

        if(Yii::$app->user->identity->access_role == ('participant')){
            if($model->pid != Yii::$app->user->identity->pid){
                Yii::$app->session->setFlash('danger', "This file does not belong to you");
                return $this->redirect(['index']);
            }
        }

        $searchModel = new PaperReviewSearch();
        $searchModel->paper_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $modelAbs = PaperReview::findOne($id);

            $out = json_encode(['output'=>'', 'message'=>'']);

            
            $posted = current($_POST['PaperReview']);
            $post = ['PaperReview' => $posted];

            
            if ($modelAbs->load($post)) {
                if($modelAbs->save())
                {
                    $out = json_encode(['output'=>'', 'message'=>'']);
                }

                else
                {
                    $error = \app\helpers\MyHelper::logError($modelAbs);
                    $out = json_encode(['output'=>'', 'message'=>'Oops, '.$error]);   
                }

                
            }
            echo $out;
            exit;
        }

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Papers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Papers();

        if(Yii::$app->user->can('participant')){
            $list_abs = Abstracts::findAll([
                'pid' => Yii::$app->user->identity->pid,
                'abs_status' => 'Accepted'
            ]);


            $model->pid = Yii::$app->user->identity->pid;

            $s3config = Yii::$app->params['s3'];
            $s3 = new \Aws\S3\S3Client($s3config);
            $errors = '';
            if ($model->load(Yii::$app->request->post())) {
                $tmp = Papers::findOne(['abs_id' => $model->abs_id]);
                if(!empty($tmp)){
                    $model = $tmp;
                    $model->load(Yii::$app->request->post());
                }

                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();            
                try {

                    $model->paper_file = UploadedFile::getInstance($model, 'paper_file');
                    if ($model->paper_file) {
                        
                        $file_name = 'FP-'.date('YmdHi').'-'.$model->abs_id.'.'.$model->paper_file->extension;
                        $s3_path = $model->paper_file->tempName;
                        $mime_type = $model->paper_file->type;
                                        
                        $key = 'snst/'.date('Y').'/fullpaper/'.$file_name;
                         
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


                    if($model->save()){

                        $transaction->commit();
                        Yii::$app->session->setFlash('success', "Data saved");
                        return $this->redirect(['index']);
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
                'list_abs' => $list_abs
            ]);
        }
    }

    /**
     * Updates an existing Papers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(Yii::$app->user->can('participant')){
            $list_abs = Abstracts::findAll([
                'pid' => Yii::$app->user->identity->pid,
                'abs_status' => 'Accepted'
            ]);


            $s3config = Yii::$app->params['s3'];
            $s3 = new \Aws\S3\S3Client($s3config);
            $paper_file = $model->paper_file;
            $errors = '';
            if ($model->load(Yii::$app->request->post())) {
                $tmp = Papers::findOne(['abs_id' => $model->abs_id]);
                if(!empty($tmp)){
                    $model = $tmp;
                    $model->load(Yii::$app->request->post());
                }

                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();            
                try {

                    $model->paper_file = UploadedFile::getInstance($model, 'paper_file');
                    if ($model->paper_file) {
                        
                        $file_name = 'FP-'.date('YmdHi').'-'.$model->abs_id.'.'.$model->paper_file->extension;
                        $s3_path = $model->paper_file->tempName;
                        $mime_type = $model->paper_file->type;
                                        
                        $key = 'snst/'.date('Y').'/fullpaper/'.$file_name;
                         
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
                        return $this->redirect(['index']);
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

            return $this->render('update', [
                'model' => $model,
                'list_abs' => $list_abs
            ]);
        }
    }

    /**
     * Deletes an existing Papers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = '';
        $model = $this->findModel($id);
        try {
            
            if($model->paper_status == 'None'){

                if(Yii::$app->user->identity->access_role == ('participant')){
                    if($model->pid != Yii::$app->user->identity->pid){
                        Yii::$app->session->setFlash('danger', "This file does not belong to you");
                        return $this->redirect(['index']);
                    }
                }
                
                $model->delete();
                $transaction->commit();
                Yii::$app->session->setFlash('success', "Data successfully deleted");    
                
            }

            else{
                throw new \Exception('Oops, this paper cannot be deleted');
                
            }
        }

        catch(\Exception $e){
                
            $errors = $e->getMessage();
            if($e->getCode() == '23000'){
                $errors = 'Oops, since this paper has been processed, it cannot be deleted ';
            }

            $transaction->rollBack();
            Yii::$app->session->setFlash('danger', $errors);
            
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Papers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Papers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Papers::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

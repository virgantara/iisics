<?php

namespace app\controllers;

use Yii;
use app\models\Abstracts;
use app\models\AbstractsSearch;
use app\models\AbstractReview;
use app\models\AbstractReviewSearch;
use app\models\Payment;
use app\models\System;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * AbstractsController implements the CRUD actions for Abstracts model.
 */
class AbstractsController extends Controller
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
                'only' => ['create','update','delete','index','my-review','ajax-change','payment'],
                'rules' => [
                    [
                        'actions' => [
                            'create','update','delete','index','payment'
                        ],
                        'allow' => true,
                        'roles' => ['admin','participant'],
                    ],

                    [
                        'actions' => [
                            'my-review'
                        ],
                        'allow' => true,
                        'roles' => ['reviewer'],
                    ],
                    [
                        'actions' => [
                            'create','update','delete','index','ajax-change'
                        ],
                        'allow' => true,
                        'roles' => ['theCreator','admin'],
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

    public function actionPayment($id)
    {
        $abs = $this->findModel($id);
        
        $s3config = Yii::$app->params['s3'];
        $s3 = new \Aws\S3\S3Client($s3config);
        
        $errors = '';
        if($abs->abs_status != 'Accepted'){
            Yii::$app->session->setFlash('danger', "This abstract has not been accepted");
            return $this->redirect(['view', 'id' => $abs->abs_id]);
        }
        
        if(Yii::$app->user->identity->access_role == ('participant')){
            if($abs->pid != Yii::$app->user->identity->pid){
                Yii::$app->session->setFlash('danger', "This file does not belong to you");
                return $this->redirect(['index']);
            }
        }

        $model = Payment::findOne([
            'pid' => $abs->pid,
            'abs_id' => $abs->abs_id
        ]);

        $pay_file = '';

        if(empty($model)){
            $model = new Payment();
            $model->abs_id = $id;
            $model->pid = $abs->pid;
        }

        else{
            $pay_file = $model->pay_file;
        }

        if ($model->load(Yii::$app->request->post())) {
            
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();            
            try {

                $model->pay_file = UploadedFile::getInstance($model, 'pay_file');
                if ($model->pay_file) {
                    
                    $file_name = 'PAYPROOF-'.'-'.date('YmdHis').'-'.$abs->abs_id.'.'.$model->pay_file->extension;
                    $s3_path = $model->pay_file->tempName;
                    $mime_type = $model->pay_file->type;
                                    
                    $key = 'snst/'.date('Y').'/payment-proof/'.$file_name;
                     
                    $insert = $s3->putObject([
                         'Bucket' => 'seminar',
                         'Key'    => $key,
                         'Body'   => 'This is the Body',
                         'SourceFile' => $s3_path,
                         'ContentType' => $mime_type
                    ]);

                    
                    $plainUrl = $s3->getObjectUrl('seminar', $key);
                    $model->pay_file = $plainUrl;
                   
                }
            

                if(empty($model->pay_file)){
                    $model->pay_file = $pay_file;
                }

                if($model->save()){
                    $system = System::findOne([
                        'sys_name' => 'seminar_finance_email'
                    ]);

                    $email = (!empty($system) ? $system->sys_content : 'pptik@unida.gontor.ac.id');

                    $emailTemplate = $this->renderPartial('email_payment_proof',[
                        'model' => $model,
                    ]);

                    Yii::$app->mailer->compose()
                        ->setTo($email)
                        ->setFrom([Yii::$app->params['supportEmail'] => 'SNST Finance'])
                        ->setSubject('[SNST] Payment Proof Uploaded')
                        ->setHtmlBody($emailTemplate)
                        ->send();
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', "Data saved");
                    return $this->redirect(['abstracts/view','id'=>$abs->abs_id]);
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

        $model->pay_currency = 'IDR';
        return $this->render('payment', [
            'model' => $model,
            'abs' => $abs
        ]);
        

        
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

            $model = Abstracts::findOne($dataPost['abs_id']);

            if(!empty($model)){
                $model->abs_status = $dataPost['abs_status'];    
                if($model->save())
                {


                    $seminar = [
                        'name' => System::findOne(['sys_name' => 'seminar_name']),
                        'alias' => System::findOne(['sys_name' => 'seminar_alias']),
                        'institution' => System::findOne(['sys_name' => 'seminar_institution']),
                    ];

                    $email = $model->p->email;
                    $emailTemplate = '';
                    if($model->abs_status == 'Accepted'){

                        $emailTemplate = $this->renderPartial('email_accept',[
                            'model' => $model,
                            'seminar' => $seminar
                        ]);
                    }

                    else if($model->abs_status == 'Rejected'){
                        $emailTemplate = $this->renderPartial('email_reject',[
                            'model' => $model,
                            'seminar' => $seminar
                        ]);
                    }
                    Yii::$app->mailer->compose()
                    ->setTo($email)
                    ->setFrom([Yii::$app->params['supportEmail'] => 'SNST Notification'])
                    ->setSubject('[SNST] Announcement')
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
        $searchModel = new AbstractReviewSearch();
        $dataProvider = $searchModel->searchMyReview(Yii::$app->request->queryParams);

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $modelAbs = AbstractReview::findOne($id);

            $out = json_encode(['output'=>'', 'message'=>'']);

            
            $posted = current($_POST['AbstractReview']);
            $post = ['AbstractReview' => $posted];

            
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

    /**
     * Lists all Abstracts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AbstractsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Abstracts model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['site/logout']);
        }

        $model = $this->findModel($id);

        if(Yii::$app->user->identity->access_role == ('participant')){
            if($model->pid != Yii::$app->user->identity->pid){
                Yii::$app->session->setFlash('danger', "This file does not belong to you");
                return $this->redirect(['index']);
            }
        }

        $searchModel = new AbstractReviewSearch();
        $searchModel->abs_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $modelAbs = AbstractReview::findOne($id);

            $out = json_encode(['output'=>'', 'message'=>'']);

            
            $posted = current($_POST['AbstractReview']);
            $post = ['AbstractReview' => $posted];

            
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
     * Creates a new Abstracts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Abstracts();

        if(Yii::$app->user->can('participant')){
            $model->pid = Yii::$app->user->identity->pid;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data saved");
            return $this->redirect(['view', 'id' => $model->abs_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Abstracts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->abs_status == 'None'){
            if(Yii::$app->user->identity->access_role == ('participant')){
                if($model->pid != Yii::$app->user->identity->pid){
                    Yii::$app->session->setFlash('danger', "This file does not belong to you");
                    return $this->redirect(['index']);
                }
            }

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', "Data saved");
                return $this->redirect(['view', 'id' => $model->abs_id]);
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        }

        else{
            Yii::$app->session->setFlash('danger', "This abstract cannot be updated");
            return $this->redirect(['view', 'id' => $model->abs_id]);
        }
    }

    /**
     * Deletes an existing Abstracts model.
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
            
            if($model->abs_status == 'None'){

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
                throw new \Exception('Oops, this abstract cannot be deleted');
                
            }
        }

        catch(\Exception $e){
                
            $errors = $e->getMessage();
            if($e->getCode() == '23000'){
                $errors = 'Oops, since this abstract has been processed, it cannot be deleted ';
            }

            $transaction->rollBack();
            Yii::$app->session->setFlash('danger', $errors);
            
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Abstracts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Abstracts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Abstracts::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

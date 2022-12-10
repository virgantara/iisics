<?php

namespace app\controllers;

use Yii;
use app\models\Abstracts;
use app\models\Participants;
use app\models\Payment;
use yii\helpers\Url;
use app\models\PaymentSearch;
use app\models\System;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

use yii\web\UploadedFile;

/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class PaymentController extends Controller
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
                'only' => ['create','update','delete','index','ajax-change','print'],
                'rules' => [
                    [
                        'actions' => [
                            'index','print','update',
                        ],
                        'allow' => true,
                        'roles' => ['admin','participant'],
                    ],
                    [
                        'actions' => [
                            'index','ajax-change','print'
                        ],
                        'allow' => true,
                        'roles' => ['finance'],
                    ],
                    [
                        'actions' => [
                            'create','update','delete','index','print'
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
        $model = Payment::findOne($id);
        if(!empty($model->pay_file)){
            try{
                $image = imagecreatefromstring($this->getImage($model->pay_file));

                header('Content-Type: image/png');
                imagepng($image);
            }

            catch(\Exception $e){
                   
            }
                
        }
        

        die();
    }

    public function actionPrint($id)
    {
        try
        {
            
            $system = System::findOne(['sys_name' => 'seminar_date_start']);
            $chairman_payment = System::findOne(['sys_name' => 'chairman_payment']);
            $model = $this->findModel($id);  

            $seminar_institution = System::findOne(['sys_name' => 'seminar_institution']);

            $keterangan = 'Title: '.(!empty($model->abs) ? $model->abs->abs_title : '');
            $year = (!empty($system) ? date('Y',strtotime($system->sys_content)) : date('Y'));
            
            $pdf = new \TCPDF('L', PDF_UNIT, 'A5', true, 'UTF-8', false);  
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(false);

            
            $pdf->AddPage();
            $imgdata = Yii::getAlias('@webroot').'/ace/assets/images/logo_snst_portrait.jpg';
            $pdf->Image($imgdata,180,10,18);

            $qr_content = "IICICS ";
            $qr_content .= (!empty($system) ? date('Y',strtotime($system->sys_content)) : date('Y'));
            $qr_content .= "\ndigitally signed by Treasurer\n";
            $qr_content .= (!empty($chairman_payment) ? $chairman_payment->sys_content : 'PIC Pembayaran Belum Diisi');
            $qr_content .= "\n".Url::toRoute('site/index',true);
            
            $writer = new PngWriter();

            $qrCode = new \Endroid\QrCode\QrCode($qr_content);
            $qrCode->setEncoding(new Encoding('UTF-8'))
                ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
                // ->setSize(300)
                ->setMargin(16)
                ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                ->setForegroundColor(new Color(0, 0, 0))
                ->setBackgroundColor(new Color(255, 255, 255))
                // Set the size of your logo, default is 48
                ->setSize(100);
                // ->setImageType(QrCode::IMAGE_TYPE_PNG)
            
            // Create generic logo
            $logo = Logo::create(Yii::getAlias('@webroot').'/ace/assets/images/icon.png')
                ->setResizeToWidth(16);

            // Create generic label
            // $label = Label::create('Label')
            //     ->setTextColor(new Color(255, 0, 0));

            $result = $writer->write($qrCode, $logo);
            $result->saveToFile(Yii::getAlias('@webroot').'/ace/assets/images/qrcode.png');
            // $qrCode->save(Yii::getAlias('@webroot').'/ace/assets/images/qrcode.png');
            $imgdata = Yii::getAlias('@webroot').'/ace/assets/images/qrcode.png';
            // $pdf->Image($imgdata,157,85,30);

            // $pdf->write2DBarcode($qr_content, 'QRCODE,H', 160, 75, 20, 30);
            ob_start();
            echo $this->renderPartial('_kop_kwitansi', [
                // 'model' => $model,
                'year' => $year,
                'seminar_institution' => $seminar_institution
            ]);

            $data = ob_get_clean();
            $font_arab_path = Yii::getAlias('@webroot').'/ace/assets/fonts/palab.ttf';
            $font_arab = \TCPDF_FONTS::addTTFfont($font_arab_path, 'TrueTypeUnicode', '', 86);
            
            $pdf->SetFont($font_arab, '', 8);
            $pdf->writeHTML($data);
            ob_start();
            echo $this->renderPartial('_print', [
                'model' => $model,
                'keterangan' => $keterangan,
                'year' => $year,
                'imgdata' => $imgdata,
                'chairman_payment'=> $chairman_payment
            ]);

            $data = ob_get_clean();
            ob_start();
            $font_arab_path = Yii::getAlias('@webroot').'/ace/assets/fonts/pala.ttf';
            $font_arab = \TCPDF_FONTS::addTTFfont($font_arab_path, 'TrueTypeUnicode', '', 86);
            
            $pdf->SetFont($font_arab, '', 7);
            $pdf->writeHTML($data);
            $pdf->Output('PF'.$model->pay_id.'-'.$model->pid.'-'.$model->abs_id.'.pdf','I');
        }
        catch(\HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
        
        die();
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

            $model = Payment::findOne($dataPost['pay_id']);

            if(!empty($model)){
                $model->pay_status = $dataPost['pay_status'];    
                if($model->save())
                {

                    $abstract = Abstracts::findOne($model->abs_id);
                    $participant = Participants::findOne($model->pid);

                    $email = $model->p->email;
                    $emailTemplate = '';
                    if($model->pay_status == 'Valid'){
                        if(!empty($abstract)){
                            $abstract->abs_paid = 1;   
                            $abstract->save();
                        }

                        if(!empty($participant)){
                            $participant->paid = 1;   
                            $participant->save();
                        }
                        $emailTemplate = $this->renderPartial('email_valid',[
                            'model' => $model,
                        ]);
                    }

                    else if($model->pay_status == 'Invalid'){
                        if(!empty($abstract)){
                            $abstract->abs_paid = 0;   
                            $abstract->save();
                        }

                        if(!empty($participant)){
                            $participant->paid = 1;   
                            $participant->save();
                        }
                        
                        $emailTemplate = $this->renderPartial('email_invalid',[
                            'model' => $model,
                        ]);
                    }
                    Yii::$app->mailer->compose()
                    ->setTo($email)
                    ->setFrom([Yii::$app->params['supportEmail'] => 'IICICS Finance'])
                    ->setSubject('[IICICS] Payment Information')
                    ->setHtmlBody($emailTemplate)
                    ->send();
                    $transaction->commit();
                    $results = [
                        'code' => 200,
                        'message' => 'An email has been sent to this participant'
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

    public function actionHistory()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
        }
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Payment models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['site/login']);
        }
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Payment model.
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
     * Creates a new Payment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Payment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data saved");
            return $this->redirect(['view', 'id' => $model->pay_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Payment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        if(!Yii::$app->user->isGuest){
            if(Yii::$app->user->identity->access_role == 'participant'){
                if($model->pid != Yii::$app->user->identity->pid){
                    Yii::$app->session->setFlash('danger', "This file does not belong to you");
                    return $this->redirect(['site/index']);
                }
            }
        }

        $abs = Abstracts::findOne($model->abs_id);;

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

        $pay_file = $model->pay_file;


        if ($model->load(Yii::$app->request->post())) {
            
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();            
            try {

                $model->pay_file = UploadedFile::getInstance($model, 'pay_file');
                if ($model->pay_file) {
                    
                    $file_name = 'PAYPROOF-'.'-'.date('YmdHis').'-'.$abs->abs_id.'.'.$model->pay_file->extension;
                    $s3_path = $model->pay_file->tempName;
                    $mime_type = $model->pay_file->type;
                                    
                    $key = 'iicics/'.date('Y').'/payment-proof/'.$file_name;
                     
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
                        ->setFrom([Yii::$app->params['supportEmail'] => 'IICICS Finance'])
                        ->setSubject('[IICICS] Payment Proof Updated')
                        ->setHtmlBody($emailTemplate)
                        ->send();
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', "Data saved");
                    return $this->redirect(['payment/view','id'=>$model->pay_id]);
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
        return $this->render('update', [
            'model' => $model,
            'abs' => $abs
        ]);
    }

    /**
     * Deletes an existing Payment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(!Yii::$app->user->isGuest){
            if(Yii::$app->user->identity->access_role == 'participant'){
                if($model->pid != Yii::$app->user->identity->pid){
                    Yii::$app->session->setFlash('danger', "This file does not belong to you");
                    return $this->redirect(['site/index']);
                }
            }
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Payment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

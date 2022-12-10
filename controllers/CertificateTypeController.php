<?php

namespace app\controllers;

use Yii;
use app\models\CertificateType;
use app\models\CertificateTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * CertificateTypeController implements the CRUD actions for CertificateType model.
 */
class CertificateTypeController extends Controller
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
                'only' => ['create','update','delete','index','download','generate'],
                'rules' => [
                    
                    [
                        'actions' => [
                            'create','update','delete','index','generate'
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
                    'generate' => ['post']
                ],
            ],
        ];
    }

    function getFile($url){
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $resource = curl_exec($ch);
        curl_close ($ch);

        return $resource;
    }

    public function actionFontFamily($id){
        $model = CertificateType::findOne($id);
        if(!empty($model->certificate_font_style)){
            try{
                $file = $this->getFile($model->certificate_font_style);

                header('Content-Type: application/octet-stream');
                echo $file;
            }

            catch(\Exception $e){
                   
            }
                
        }
        

        die();
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
        $model = CertificateType::findOne($id);
        if(!empty($model->certificate_template)){
            try{
                $image = imagecreatefromstring($this->getImage($model->certificate_template));

                header('Content-Type: image/png');
                imagepng($image);
            }

            catch(\Exception $e){
                   
            }
                
        }
        

        die();
    }

    /**
     * Lists all CertificateType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CertificateTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPreview($id)
    {
        $model = $this->findModel($id);
        try{
            $pdf = new \TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);  
            
            $pdf->AddPage();
            $img_file = $model->certificate_template;
            // get the current page break margin
            $bMargin = $pdf->getBreakMargin();
            // get current auto-page-break mode
            $auto_page_break = $pdf->getAutoPageBreak();
            // disable auto-page-break
            $pdf->SetAutoPageBreak(false, 0);
            // set bacground image
            // $img_file = K_PATH_IMAGES.'image_demo.jpg';
            $pdf->Image($img_file, 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight(), '', '', '', false, 300, '', false, false, 0);
            // restore auto-page-break status
            $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
            // set the starting point for the page content
            $pdf->setPageMark();


            // Print a text
            // $html = 'The quick brown fox jumps over the lazy dog';
            // $pdf->writeHTML($html, true, false, true, false, '');
            $font_path = $model->certificate_font_style;
            $font = \TCPDF_FONTS::addTTFfont($font_path, 'TrueTypeUnicode', '', 86);
            
            $pdf->SetFont($font, '', $model->certificate_font_size);
            $txt = 'The quick brown fox jumps over the lazy dog';
            $text_width = $pdf->GetStringWidth($txt,'','B');
            $pdf->SetXY($pdf->getPageWidth() / 2 - $text_width / 2, $model->certificate_text_top_position);
            $pdf->writeHTML($txt);
            $pdf->Output('template.pdf','I');
        }
        
        catch(\Exception $e) {
            print_r($e->getMessage());
            exit;
        }

    }

    /**
     * Displays a single CertificateType model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
       
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new CertificateType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CertificateType();

        $s3config = Yii::$app->params['s3'];
        $s3 = new \Aws\S3\S3Client($s3config);
        $errors = '';
        if ($model->load(Yii::$app->request->post())) {
            
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();            
            try {

                $model->certificate_template = UploadedFile::getInstance($model, 'certificate_template');
                if ($model->certificate_template) {
                    
                    $file_name = 'CT-'.date('YmdHis').rand(1,100).'.'.$model->certificate_template->extension;
                    $s3_path = $model->certificate_template->tempName;
                    $mime_type = $model->certificate_template->type;
                                    
                    $key = 'iicics/'.date('Y').'/certificate/template/'.$file_name;
                     
                    $insert = $s3->putObject([
                         'Bucket' => 'seminar',
                         'Key'    => $key,
                         'Body'   => 'This is the Body',
                         'SourceFile' => $s3_path,
                         'ContentType' => $mime_type
                    ]);

                    
                    $plainUrl = $s3->getObjectUrl('seminar', $key);
                    $model->certificate_template = $plainUrl;
                   
                }

                $model->certificate_font_style = UploadedFile::getInstance($model, 'certificate_font_style');
                if ($model->certificate_font_style) {
                    
                    $file_name = 'FONT-'.date('YmdHis').rand(1,100).'.'.$model->certificate_font_style->extension;
                    $s3_path = $model->certificate_font_style->tempName;
                    $mime_type = $model->certificate_font_style->type;
                                    
                    $key = 'iicics/'.date('Y').'/certificate/fonts/'.$file_name;
                     
                    $insert = $s3->putObject([
                         'Bucket' => 'seminar',
                         'Key'    => $key,
                         'Body'   => 'This is the Body',
                         'SourceFile' => $s3_path,
                         'ContentType' => $mime_type
                    ]);

                    
                    $plainUrl = $s3->getObjectUrl('seminar', $key);
                    $model->certificate_font_style = $plainUrl;
                   
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
     * Updates an existing CertificateType model.
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
        $certificate_template = $model->certificate_template;
        $certificate_font_style = $model->certificate_font_style;
        $errors = '';
        if ($model->load(Yii::$app->request->post())) {
            
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();            
            try {

                $model->certificate_template = UploadedFile::getInstance($model, 'certificate_template');
                if ($model->certificate_template) {
                    
                    $file_name = 'CT-'.date('YmdHis').rand(1,100).'.'.$model->certificate_template->extension;
                    $s3_path = $model->certificate_template->tempName;
                    $mime_type = $model->certificate_template->type;
                                    
                    $key = 'iicics/'.date('Y').'/certificate/template/'.$file_name;
                     
                    $insert = $s3->putObject([
                         'Bucket' => 'seminar',
                         'Key'    => $key,
                         'Body'   => 'This is the Body',
                         'SourceFile' => $s3_path,
                         'ContentType' => $mime_type
                    ]);

                    
                    $plainUrl = $s3->getObjectUrl('seminar', $key);
                    $model->certificate_template = $plainUrl;
                   
                }            

                if(empty($model->certificate_template)){
                    $model->certificate_template = $certificate_template;
                }

                $model->certificate_font_style = UploadedFile::getInstance($model, 'certificate_font_style');
                if ($model->certificate_font_style) {
                    
                    $file_name = 'FONT-'.date('YmdHis').rand(1,100).'.'.$model->certificate_font_style->extension;
                    $s3_path = $model->certificate_font_style->tempName;
                    $mime_type = $model->certificate_font_style->type;
                                    
                    $key = 'iicics/'.date('Y').'/certificate/fonts/'.$file_name;
                     
                    $insert = $s3->putObject([
                         'Bucket' => 'seminar',
                         'Key'    => $key,
                         'Body'   => 'This is the Body',
                         'SourceFile' => $s3_path,
                         'ContentType' => $mime_type
                    ]);

                    
                    $plainUrl = $s3->getObjectUrl('seminar', $key);
                    $model->certificate_font_style = $plainUrl;
                   
                }            

                if(empty($model->certificate_font_style)){
                    $model->certificate_font_style = $certificate_font_style;
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
     * Deletes an existing CertificateType model.
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
     * Finds the CertificateType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CertificateType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CertificateType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

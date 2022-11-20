<?php

namespace app\controllers;

use Yii;
use app\helpers\MyHelper;
use app\models\Abstracts;
use app\models\Certificate;
use app\models\CertificateType;
use app\models\CertificateSearch;
use app\models\System;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CertificateController implements the CRUD actions for Certificate model.
 */
class CertificateController extends Controller
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
                'only' => ['create','update','delete','index','download','generate','print'],
                'rules' => [
                    
                    [
                        'actions' => [
                            'create','update','delete','index','generate','print'
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
                    // 'generate' => ['post']
                ],
            ],
        ];
    }

    public function actionPrint($id)
    {

        $model = $this->findModel($id);
        try{
            $cert_type = CertificateType::findOne($model->type_id);
            $pdf = new \TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);  
            
            $pdf->AddPage();
            $img_file = $cert_type->certificate_template;
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
            $font_path = $cert_type->certificate_font_style;
            $font = \TCPDF_FONTS::addTTFfont($font_path, 'TrueTypeUnicode', '', 86);
            
            $pdf->SetFont($font, '', $cert_type->certificate_font_size);
            $txt = $model->name;
            $text_width = $pdf->GetStringWidth($txt,'','B');
            $pdf->SetXY($pdf->getPageWidth() / 2 - $text_width / 2, $cert_type->certificate_text_top_position);
            $pdf->writeHTML($txt);
            $pdf->Output($model->name.'.pdf','I');
        }
        
        catch(\Exception $e) {
            print_r($e->getMessage());
            exit;
        }

    }


    public function actionGenerate()
    {

        $model = new CertificateType;
        $list_types = CertificateType::find()->all();
        $transaction = Yii::$app->db->beginTransaction();
        try{


            if ($model->load(Yii::$app->request->post())) {

                $certificate_no = System::findOne(['sys_name' => 'certificate_no']);

                $cert_type = CertificateType::findOne($_POST['CertificateType']['id']);
                $list_abs = Abstracts::findAll([
                    'abs_status' => 'Accepted'
                ]);
                
                // $cert_type = CertificateType::findOne($model->type_id);

                foreach($list_abs as $abs){
                    $cert_no = $abs->pid.'.'.$abs->abs_id.$cert_type->certificate_prefix_number.$certificate_no->sys_content;
                    $m = Certificate::findOne([
                        'cert_no' => $cert_no
                    ]);

                    if(empty($m))
                        $m = new Certificate;

                    $m->cert_no = $cert_no;
                    $m->pid = $abs->pid;
                    $m->abs_id = $abs->abs_id;
                    $m->name = (!empty($abs->p) ? $abs->p->name : '');
                    $m->type_id = $cert_type->id;
                    if(!$m->save()){
                        throw new \Exception(MyHelper::logError($m));
                                        
                    }


                }
                $transaction->commit();
                Yii::$app->session->setFlash("success","Certificates have been generated");
                
                return $this->redirect(['index']);    
            }
        }

        catch(\Exception $e) {
            $errors .= $e->getMessage();
            $transaction->rollBack();
            Yii::$app->session->setFlash("danger",$errors);
            return $this->redirect(['generate']);          
        }
        return $this->render('generate',[
            'model' => $model,
            'list_types' => $list_types
        ]);

    }   

    /**
     * Lists all Certificate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CertificateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Certificate model.
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
     * Creates a new Certificate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Certificate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data saved");
            return $this->redirect(['view', 'id' => $model->cert_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Certificate model.
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
            return $this->redirect(['view', 'id' => $model->cert_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Certificate model.
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
     * Finds the Certificate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Certificate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Certificate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

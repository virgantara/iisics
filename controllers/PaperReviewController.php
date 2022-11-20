<?php

namespace app\controllers;

use Yii;
use app\helpers\MyHelper;
use app\models\User;
use app\models\PaperReview;
use app\models\PaperReviewSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * PaperReviewController implements the CRUD actions for PaperReview model.
 */
class PaperReviewController extends Controller
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
                'only' => ['create','update','delete','index','ajax-add'],
                'rules' => [
                    [
                        'actions' => [
                            'create','update','delete','index','ajax-add'
                        ],
                        'allow' => true,
                        'roles' => ['admin'],
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
        $file = $model->file_path;
        if(empty($model->file_path)){
            Yii::$app->session->setFlash('danger', 'Oops, this file does not exist');
            return $this->redirect(['index']);
        }
        $filename = 'FP-REV-'.$model->rev_id.'-'.$model->abs_id.'.pdf';

        // Header content type
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
          
        // Read the file
        @readfile($file);
        exit;
    }

    public function actionAjaxAdd()
    {
        $results = [];

        $errors = '';
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        

        try 
        {
            $dataPost = $_POST;

            
            $model = new PaperReview;
            $model->attributes = $dataPost;
            
            if($model->save())
            {
                $password = MyHelper::getRandomString(6,6);
                $reviewer = $model->rev;
                $user = User::findByEmail($reviewer->rev_email);

                if(empty($user)){
                    
                    $user = new User;
                    $user->username = $reviewer->rev_email;
                    $user->fullname = $reviewer->rev_name;
                    $user->email = $reviewer->rev_email;
                    $user->setPassword($password);
                    $user->generateAuthKey();
                    $user->status = User::STATUS_ACTIVE;
                    $user->rev_id = $reviewer->rev_id;
                    $user->access_role = 'reviewer';
                }

                $user->setPassword($password);
                $user->generateAuthKey();

                if(!$user->save()){
                    throw new Exception(MyHelper::logError($user));
                }

                $userId = $user->id;
                $email = $user->email;
                $auth = Yii::$app->authManager;

                $is_exist = false;
                if ($roles = $auth->getRolesByUser($userId)) {
                
                    $role = array_keys($roles);
                    foreach($role as $r){
                        if($r == 'reviewer'){
                            $is_exist = true;
                            break;
                        }
                    } 
                }
                $newRole = $auth->getRole('reviewer');
                $info = '';
                if($is_exist){

                    if ($auth->revoke($newRole, $userId)) {
                        $info = $auth->assign($newRole, $userId);
                    }
                }

                else{
                    $info = $auth->assign($newRole, $userId);
                }

                if (!$info) {
                    throw new Exception("There was some error while saving user role");
                    
                }


                $emailTemplate = $this->renderPartial('email_assign',[
                    'reviewer' => $model->rev,
                    'abstract' => $model->abs,
                    'paper' => $model->paper,
                    'username' => $user->username,
                    'password' => $password
                ]);

                Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([Yii::$app->params['supportEmail'] => 'SNST Technical Program Committee'])
                ->setSubject('[SNST] Fullpaper Reviewer Assignment')
                ->setHtmlBody($emailTemplate)
                ->send();
                $transaction->commit();
                $results = [
                    'code' => 200,
                    'message' => 'Data successfully added'
                ]; 
                
            }

            else
            {
                $errors .= MyHelper::logError($model);
                throw new \Exception;
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


    /**
     * Lists all PaperReview models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaperReviewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PaperReview model.
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
     * Creates a new PaperReview model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PaperReview();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Data saved");
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PaperReview model.
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
            return $this->redirect(['papers/my-review']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PaperReview model.
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
     * Finds the PaperReview model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PaperReview the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PaperReview::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

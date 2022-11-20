<?php

namespace app\controllers;

use Yii;
use app\rbac\models\AuthItem;
use app\helpers\MyHelper;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\filters\AccessControl;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException('You are not allowed to access this page');
                },
                'only' => ['create','update','delete','index','view','otp'],
                'rules' => [
                    [
                        'actions' => ['index','view','otp'],
                        'allow' => true,
                        'roles' => ['fakultas','tatausaha','prodi']
                    ],
                    [
                        'actions' => ['create','update','delete','index','view','otp'],
                        'allow' => true,
                        'roles' => ['theCreator','admin']
                    ]
                ]
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goBack();
        }else{  
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        
        if (Yii::$app->request->post('hasEditable')) {
            // instantiate your book model for saving
            $id = Yii::$app->request->post('editableKey');
            $model = User::findOne($id);

            // store a default json response as desired by editable
            $out = json_encode(['output'=>'', 'message'=>'']);

            
            $posted = current($_POST['User']);
            $post = ['User' => $posted];

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
            return;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
        }
    }

    public function actionCreateFakultas()
    {
        $user = new User;
        $user->scenario = 'sce_satker';
        $auth = Yii::$app->authManager;

        $authItems = AuthItem::find()->select(['name'])->where(['<>','name','theCreator'])->all();

        // get user role if he has one  
        
        // if user has role, set oldRole to that role name, else offer 'member' as sensitive default
        

        // set property item_name of User object to this role name, so we can use it in our form
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $counter = 0;
        $errors = '';
        try 
        {
            if ($user->load(Yii::$app->request->post())) 
            {
                $oldRole = $auth->getRole($user->access_role);
                // only if user entered new password we want to hash and save it
                if ($user->password) {
                    $user->setPassword($user->password);
                }

                $user->NIY = $user->username;
                $user->created_at = strtotime(date('Y-m-d H:i:s'));
                $user->updated_at = strtotime(date('Y-m-d H:i:s'));
                
                $satker = \app\models\UnitKerja::findOne($user->satker_id);

                if(!empty($satker))
                {
                    $kode_prodi = $satker->kode_prodi;

                    $prodi = \app\models\Prodi::find()->where(['kode_prod'=>$kode_prodi])->one();

                    if(!empty($prodi))
                    {
                        $user->id_prod = $prodi->ID;
                    }
                }

                // $user->access_role = $user->item_name;
                if (!$user->save()) {
                    $errors .= \app\helpers\MyHelper::logError($user);
                    throw new \Exception;
                    
                    
                }

                $newRole = $auth->getRole($user->access_role);
                // get user id too

                $userId = $user->getId();
                
                $info = $auth->assign($newRole, $userId);
                

                if (!$info) {
                    $errors .= \app\helpers\MyHelper::logError($user);
                    throw new \Exception;
                    
                }
                

                $transaction->commit();
                return $this->redirect(['index']);
            }
        }

        catch(\Exception $e)
        {
            $errors .= $e->getMessage();
            Yii::$app->session->setFlash('danger',$errors);
        }


        return $this->render('form_satker', [
            'user' => $user, 
            'role' => $user->item_name,
            'authItems' => $authItems
        ]);
    }

    public function actionOtp($id)
    {
        $user = User::find()->where(['ID'=>$id])->one();

        if(!empty($user))
        {
            $user->otp = Yii::$app->security->generateRandomString() . '_' . time();
            $user->save(false,['otp']);

            return $this->redirect(Yii::$app->params['front'].'/site/login-otp?otp='.$user->otp);
        }

        else{
            return $this->redirect(['view','id'=>$id]);
        }
    }


    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $user = new User(['scenario' => 'create']);

        if (!$user->load(Yii::$app->request->post())) {
            return $this->render('create', ['user' => $user]);
        }

        $user->setPassword($user->password);
        $user->generateAuthKey();
        // $user->item_name = $user->access_role;
        if (!$user->save()) {
            return $this->render('create', ['user' => $user]);
        }

        $auth = Yii::$app->authManager;
        $role = $auth->getRole($user->access_role);
        $info = $auth->assign($role, $user->getId());

        if (!$info) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'There was some error while saving user role.'));
        }

       

        return $this->redirect(['index']);
    }
    public function actionAjaxDeleteRole()
    {
        $dataPost = $_POST['dataPost'];
        $results = [];
        $user = $this->findModel($dataPost['user_id']);

        
        if(!empty($user))
        {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            $errors = '';
            try 
            {
                $items = \app\rbac\models\AuthAssignment::find()->where([
                    'user_id' => $user->id
                ])->all();

                if(count($items) > 0)
                {
                    

                    // if($user->access_role != $dataPost['item_name'])
                    // {
                        $auth = Yii::$app->authManager;
                        if ($roles = $auth->getRolesByUser($user->id)) {
                            // it's enough for us the get first assigned role name
                            $role = '';
                            foreach($roles as $r)
                            {
                                
                                if($r->name == $dataPost['item_name'])
                                {
                                    $role = $r->name;
                                    break;
                                }
                            }

                        }

                        // print_r($roles);exit;

                        // remove role if user had it
                        if (isset($role)) {
                           
                            $info = $auth->revoke($auth->getRole($role), $user->id);
                        }

                        if(!$info)
                        {
                            $errors .= 'Something wrong when deleting role';
                            throw new \Exception;
                        }

                        $transaction->commit();
                        $results = [
                            'code' => 200,
                            'message' => 'Role deleted'
                        ];
                    // }
                    
                    // else
                    // {
                    //     $errors .= 'Cannot delete assigned role';
                    //     throw new \Exception;
                    // }
                }

                else
                {
                    $errors .= 'Cannot delete the last role';
                    throw new \Exception;
                    
                }

            } 

            catch (\Exception $e) {
                $errors .= $e->getMessage();
                $transaction->rollBack();
                
                $results = [
                    'code' => 500,
                    'message' => $errors
                ];
                
            } catch (\Throwable $e) {
                $errors .= $e->getMessage();
                $transaction->rollBack();
                $results = [
                    'code' => 500,
                    'message' => $errors
                ];
            }
            
        }

        else
        {
            $results = [
                'code' => 500,
                'message' => 'User not found'
            ];
        }

        echo json_encode($results);
        exit;
    }

    public function actionAjaxAddRole()
    {
        $dataPost = $_POST['dataPost'];
        $results = [];
        $user = $this->findModel($dataPost['user_id']);

        if(!empty($user))
        {
            $auth = Yii::$app->authManager;

            $newRole = $auth->getRole($dataPost['item_name']);
            $userId = $user->getId();
            $info = $auth->assign($newRole, $userId);
            

            if (!$info) {
                $errors .= 'There was some error while saving user role.';
                throw new \Exception;
            }

            
            $results = [
                'code' => 200,
                'message' => 'Data added'
            ];
            

        }

        else
        {
            $results = [
                'code' => 500,
                'message' => 'User not found'
            ];
        }

        echo json_encode($results);
        exit;
    }

   
    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
  

        $user = $this->findModel($id);
        
        if (!$user->load(Yii::$app->request->post())) {
            return $this->render('create', ['user' => $user]);
        }

        if(!empty($user->password)){


            $user->setPassword($user->password);
            $user->generateAuthKey();
        }
        // $user->item_name = $user->access_role;
        if (!$user->save()) {
            return $this->render('update', ['user' => $user]);
        }

        $auth = Yii::$app->authManager;
        $role = $auth->getRole($user->access_role);
        if(empty($role)){
            $info = $auth->assign($role, $user->getId());

            if (!$info) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'There was some error while saving user role.'));
            }
        }

        return $this->redirect(['index']);
        
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $auths = \app\rbac\models\AuthAssignment::find()->where(['user_id' => $id])->all();
        foreach($auths as $auth)
        {
            $auth->delete();
        }

        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    function actionMusnah($folderhapus) {
    $files = glob( $folderhapus . '*', GLOB_MARK );
    foreach( $files as $file ){
        if( substr( $file, -1 ) == '/' )
            linuxfun_hapussemua( $file );
        else
            unlink( $file );
    }

    if (is_dir($folderhapus)) rmdir( $folderhapus );

    }
}
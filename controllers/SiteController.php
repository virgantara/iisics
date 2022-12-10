<?php
namespace app\controllers;

use Yii;

use app\helpers\MyHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\AccountActivation;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;

use app\models\Faq;
use app\models\Homecontent;
use app\models\Pages;
use app\models\Participants;
use app\models\ScheduleDay;
use app\models\ScheduleTime;
use app\models\Speakers;
use app\models\Sponsor;
use app\models\System;
use app\models\User;

use \Firebase\JWT\JWT;
use app\models\Menu;
use yii\httpclient\Client;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public $successUrl = '';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException('You are not allowed to access this page');
                },
                'only' => ['logout', 'signup','testing','dashboard'],
                'rules' => [
                    [
                        'actions' => [
                            'testing'
                        ],
                        'allow' => true,
                        'roles' => ['theCreator'],
                    ],
                    [
                        'actions' => ['signup','test'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','dashboard'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
                'successUrl' => $this->successUrl
            ],
        ];
    }



    public function actionDashboard()
    {
        // $this->layout = 'main';
        return $this->render('dashboard',[
        ]);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {   
        $this->layout = 'event';
        $menu = Menu::find()->orderBy(['sequence'=>SORT_ASC])->all();

     
        $about = Pages::findOne(['page_slug' => 'about']);
        $topics = Pages::findOne(['page_slug' => 'scopes-of-paper']);
        $schedule = Pages::findOne(['page_slug' => 'schedule']);
        $contact = Pages::findOne(['page_slug' => 'contact']);
        $registration = Pages::findOne(['page_slug' => 'registration-fees']);
        $speakers = Speakers::find()->orderBy(['sequence'=>SORT_ASC])->all();
        $schedule_day = ScheduleDay::find()->orderBy(['sequence'=>SORT_ASC])->all();
        $venue = Homecontent::findOne(['page_slug' => 'venue']);
        $sponsors = Sponsor::find()->orderBy(['sequence'=>SORT_ASC])->all();

        $abstract_guidelines = Pages::findOne(['page_slug' => 'abstract-guidelines']);
        $paper_guidelines = Pages::findOne(['page_slug' => 'paper-guidelines']);
        
        $seminar = [
            'name' => System::findOne(['sys_name' => 'seminar_name']),
            'alias' => System::findOne(['sys_name' => 'seminar_alias']),
            'institution' => System::findOne(['sys_name' => 'seminar_institution']),
            'date_start' => System::findOne(['sys_name' => 'seminar_date_start']),
            'date_end' => System::findOne(['sys_name' => 'seminar_date_end']),
            'email' => System::findOne(['sys_name' => 'seminar_email']),
            'city' => System::findOne(['sys_name' => 'seminar_city']),
            'website' => System::findOne(['sys_name' => 'seminar_website']),
        ];

        $faqs = Faq::find()->orderBy(['faq_sequence'=>SORT_ASC])->all();
        return $this->render('index',[
            'menu' => $menu,
            'paper_guidelines' => $paper_guidelines,
            'abstract_guidelines' => $abstract_guidelines,
            'registration' => $registration,
            'contact' => $contact,
            'faqs' => $faqs,
            'topics' => $topics,
            'about' => $about,
            'venue' => $venue,
            'seminar' => $seminar,
            'sponsors' => $sponsors,
            'schedule' => $schedule,
            'speakers' => $speakers,
            'schedule_day' => $schedule_day,
        ]);

    }


    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {   

        $this->layout = 'default';

        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['dashboard']);
        }

        $seminar = [
            'name' => System::findOne(['sys_name' => 'seminar_name']),
            'alias' => System::findOne(['sys_name' => 'seminar_alias']),
            'institution' => System::findOne(['sys_name' => 'seminar_institution']),
        ];

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            
            return $this->redirect(['dashboard']);
        } else {
            return $this->render('login', [
                'model' => $model,
                'seminar' => $seminar
            ]);
        }
    }

    public function actionLoginOtp($id, $token)
    {
        $user = \app\models\User::find()
            ->where([
                'otp'=>$token,
            ])
            ->one();

        if(!empty($user))
        {
            // 
            if(Yii::$app->user->login($user))
            {
                if($user->access_role=='Dosen')
                {
                    $userId = $user->getId();
                    $auth = Yii::$app->authManager;

            
                    if ($roles = $auth->getRolesByUser($userId)) {
                        // it's enough for us the get first assigned role name
                        $role = array_keys($roles)[0]; 
                    }

                    // if user has role, set oldRole to that role name, else offer 'member' as sensitive default
                    $oldRole = (isset($role)) ? $auth->getRole($role) : $auth->getRole('Dosen');

                    // set property item_name of User object to this role name, so we can use it in our form
                    
                    
                    // only if user entered new password we want to hash and save it
                    $user->item_name = 'reviewer';
                    $user->access_role = 'reviewer';
                    $user->otp = '';
                    $user->otp_expire = '';
                    
                    if (!$user->save()) {
                        Yii::$app->session->setFlash('error', Yii::t('app', 'Mohon maaf, login Anda tidak valid'));
            
                        return $this->redirect(['site/login']);
                    }

                    // take new role from the form
                    $newRole = $auth->getRole($user->item_name);
                    // get user id too
                    
                    // we have to revoke the old role first and then assign the new one
                    // this will happen if user actually had something to revoke
                    if ($auth->revoke($oldRole, $userId)) {
                        $info = $auth->assign($newRole, $userId);
                    }

                    // in case user didn't have role assigned to him, then just assign new one
                    if (!isset($role)) {
                        $info = $auth->assign($newRole, $userId);
                    }

                    if (!$info) {
                        Yii::$app->session->setFlash('error', Yii::t('app', 'There was some error while saving user role.'));
                    }

                    
                }

                return $this->redirect(['penelitian/review','id'=>$id]);
            }


            
        }

        else{
            Yii::$app->session->setFlash('error', Yii::t('app', 'Mohon maaf, login Anda tidak valid'));
            
            return $this->redirect(['site/login']);
        }
  
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }




    public function actionRequestPasswordReset()
    {
        $this->layout = 'default';
        $model = new PasswordResetRequestForm();
        $seminar = [
            'name' => System::findOne(['sys_name' => 'seminar_name']),
            'alias' => System::findOne(['sys_name' => 'seminar_alias']),
            'institution' => System::findOne(['sys_name' => 'seminar_institution']),
        ];
        if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
            return $this->render('requestPasswordResetToken', [
                'model' => $model,
                'seminar' => $seminar
            ]);
        }

        if (!$model->sendEmail()) {
            Yii::$app->session->setFlash('error', Yii::t('app', 
                'Sorry, we are unable to reset password for email provided.'));
            return $this->refresh();
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'Silakan cek inbox email Anda. Jika tidak ada, mohon cek spam Anda'));

        return $this->goHome();
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if (!$model->load(Yii::$app->request->post()) || !$model->validate() || !$model->resetPassword()) {
            return $this->render('resetPassword', ['model' => $model]);
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'New password was saved.'));

        return $this->goHome();      
    }    

    public function actionSignup()
    {  
        $participant = new Participants;

        // $participant->name = "Oddy Virgantara Putra";
        // $participant->name2 = "Oddy Virgantara Putra,  S.Kom., M.T.";
        // $participant->gender = "Male";
        // $participant->type = "Presenter";
        // $participant->institution = "UNIDA Gontor";
        // $participant->address = "Mlarak, Ponorogo";
        // $participant->country = "Indonesia";
        // $participant->phone = "08563667286";
        // $participant->fax = "08563667286";
        // get setting value for 'Registration Needs Activation'
        $rna = Yii::$app->params['rna'];

        // if 'rna' value is 'true', we instantiate SignupForm in 'rna' scenario
        $model = $rna ? new SignupForm(['scenario' => 'rna']) : new SignupForm();

        // $model->username = "oddyvirgantara";
        // $model->email = "oddy@unida.gontor.ac.id";
        $participant->email = $model->email;
        // $model->password = "oddyvirgantara";
        // if validation didn't pass, reload the form to show errors
        if (!$model->load(Yii::$app->request->post()) || !$model->validate()) {
            return $this->render('signup', [
                'model' => $model,
                'participant' => $participant
            ]);  
        }

        $transaction = Yii::$app->db->beginTransaction();
        try 
        {
            $participant->email = $model->email;
            if($participant->load(Yii::$app->request->post()) && $participant->save()){

                // try to save user data in database, if successful, the user object will be returned
                $user = $model->signup();

                if (!$user) {
                    // display error message to user
                    Yii::$app->session->setFlash('error', Yii::t('app', 'We couldn\'t sign you up, please contact us.'));
                    return $this->refresh();
                }

                $user->pid = $participant->pid;
                $user->fullname = $participant->name;
                $user->save();

                // user is saved but activation is needed, use signupWithActivation()
                if ($user->status === User::STATUS_INACTIVE) {
                    $this->signupWithActivation($model, $user);
                    $transaction->commit();
                    return $this->refresh();
                }

                // now we will try to log user in
                // if login fails we will display error message, else just redirect to home page
            
                if (!Yii::$app->user->login($user)) {
                    // display error message to user
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Please try to log in.'));

                    // log this error, so we can debug possible problem easier.
                    Yii::error('Login after sign up failed! User '.Html::encode($user->username).' could not log in.');
                }
            }

            else{
                throw new \Exception(MyHelper::logError($participant));
                
            }
        }

        catch(\Exception $e)
        {
            $errors = $e->getMessage();
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', Yii::t('app', $errors));
            return $this->refresh();
        }        
        return $this->goHome();
    }

    private function signupWithActivation($model, $user)
    {
        // sending email has failed
        if (!$model->sendAccountActivationEmail($user)) {
            // display error message to user
            Yii::$app->session->setFlash('error', Yii::t('app', 
                'We couldn\'t send you account activation email, please contact us.'));

            // log this error, so we can debug possible problem easier.
            Yii::error('Signup failed! User '.Html::encode($user->username).' could not sign up. 
                Possible causes: verification email could not be sent.');
        }

        // everything is OK
        Yii::$app->session->setFlash('success', Yii::t('app', 'Hello').' '.Html::encode($user->username). '. ' .
            Yii::t('app', 'To be able to log in, you need to confirm your registration. 
                Please check your email, we have sent you a message.'));
    }

    /**
     * Activates the user account so he can log in into system.
     *
     * @param  string $token
     * @return \yii\web\Response
     *
     * @throws BadRequestHttpException
     */
    public function actionActivateAccount($token)
    {
        try {
            $user = new AccountActivation($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if (!$user->activateAccount()) {
            Yii::$app->session->setFlash('error', Html::encode($user->username). Yii::t('app', 
                ' your account could not be activated, please contact us!'));
            return $this->goHome();
        }

        Yii::$app->session->setFlash('success', Yii::t('app', 'Success! You can now log in.').' '.
            Yii::t('app', 'Thank you').' '.Html::encode($user->username).' '.Yii::t('app', 'for joining us!'));

        return $this->redirect('login');
    }

    private function sendmail($email)
    {
        $emailTemplate = $this->renderPartial('email_signup');
        Yii::$app->mailer->compose()
        ->setTo($email)
        ->setFrom([Yii::$app->params['supportEmail'] => 'IICICS '.date('Y')])
        ->setSubject('Registration IICICS')
        ->setHtmlBody($emailTemplate)
        ->send();
    }
}

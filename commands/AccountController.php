<?php

namespace app\commands;

use Yii;
use app\models\User;
use app\models\DataDiri;
use app\models\Tendik;
use app\models\JenisTendik;
use app\models\MJenjangPendidikan;
use app\models\TendikSearch;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\web\NotFoundHttpException;


/**
 * TendikController implements the CRUD actions for Tendik model.
 */
class AccountController extends Controller
{

    public function actionUpdateGelarDosen()
    {
        $list = DataDiri::find()->all();
        $transaction = Yii::$app->db->beginTransaction();
            // exit;
        $errors = '';
        try 
        {
            $counter = 0;
            foreach($list as $d)
            {
                if(strlen($d->NIY) <=2 ) continue;

                if (strpos($d->nama, ',') !== false || strpos($d->nama, '.') !== false) 
                {
    
                    $gelars = explode(',', $d->nama);
                    
                    $gelar_else = '';

                    $suffix = ', ';
                    for($i = 1;$i<count($gelars);$i++)
                    {
                        if($i==count($gelars)-1)
                            $suffix = '';

                        $gelar_else .= trim($gelars[$i]).$suffix;
                    }

                    $tmp = $gelars[0];
                    // $d->nama = $gelars[0];
                    $d->gelar_belakang = $gelar_else;

                    if(strpos($tmp, '.') !== false)
                    {
                        $suffix = '. ';
                        $gelars = explode('.', $tmp);
                        $gelar_depan = '';
                        for($i = 0;$i<count($gelars)-1;$i++)
                        {
                            if($i==count($gelars)-2)
                                $suffix = '.';

                            $gelar_depan .= trim($gelars[$i]).$suffix;
                        }    

                        $d->gelar_depan = $gelar_depan;
                        $tmp = $gelars[count($gelars)-1];
                    }

                    // $d->nama = $tmp;
                   
                    if($d->save(false,['nama','gelar_belakang','gelar_depan']))
                    {
                        $counter++;
                    }

                    else{
                        $errors .= \app\helpers\MyHelper::logError($d).$d->nama;
                        throw new \Exception;
                    }

                }
            }
            echo 'Data updated '.$counter;
            $transaction->commit();
        }

        catch(\Exception $e)
        {
            $errors .= $e->getMessage();
            $transaction->rollBack();
            echo $errors;
        }

        echo "\n";
        return ExitCode::OK;
    }

    public function actionSyncAccountTendik()
    {

        $counter = 0;
        $transaction = Yii::$app->db->beginTransaction();
        $errors = '';
        try 
        {
            $auth = Yii::$app->authManager;
            $listTendik = Tendik::find()->select(['NIY','nama'])->all();
            foreach($listTendik as $m)
            {
                $model = User::find()->where(['NIY'=>$m->NIY])->one();
                if(empty($model))
                {
                    $model = new User;
                    $email = preg_replace('/[^a-zA-Z0-9_]/', '', strtolower($m->nama));
                    $model->email = $email.'@staff.unida.gontor.ac.id';
                    $model->uuid = \app\helpers\MyHelper::gen_uuid();
                     
                    $model->status = 'aktif';
                    $model->status_admin = 'user';
                    $model->username = $email;
                    $model->NIY = $m->NIY;
                }
                $model->access_role = 'Staf';
                $model->password_hash = Yii::$app->security->generatePasswordHash($m->NIY);
                $model->auth_key = Yii::$app->security->generateRandomString();    
                $model->created_at = strtotime(date('Y-m-d H:i:s'));
                $model->updated_at = strtotime(date('Y-m-d H:i:s'));
                
                if($model->save())
                {
                    if ($roles = $auth->getRolesByUser($model->ID)) {
                        // it's enough for us the get first assigned role name
                        $role = array_keys($roles)[0]; 
                    }
                    $oldRole = (isset($role)) ? $auth->getRole($role) : $auth->getRole('Staf');
                     // print_r($oldRole);exit;
                    $newRole = $auth->getRole($model->access_role);
                    if ($auth->revoke($oldRole, $model->ID)) {
                        
                        $info = $auth->assign($newRole, $model->ID);
                    }

                    if (!isset($role)) {
                        $info = $auth->assign($newRole, $model->ID);
                    }

                    if (!$info) {
                        $errors .= 'There was some error while saving user role.';
                        throw new \Exception;
                       
                    }
                    $counter++;
                }

                else
                {
                    $errors .= \app\helpers\MyHelper::logError($model).$model->email;
                    throw new \Exception;
                }
            }
            
            $transaction->commit();
            echo $counter.' data updated';
        
        } 

        catch (\Exception $e) 
        {
            $transaction->rollBack();
            $errors .= $e->getMessage();
            
            echo $errors;
        }
        echo "\n";
        return ExitCode::OK;
    }

    public function actionSetDefaultUserDosen()
    {
        $list = User::find()->all();
        $auth = Yii::$app->authManager;
        $transaction = Yii::$app->db->beginTransaction();
            // exit;
        $errors = '';
        try 
        {
            $counter = 0;
            foreach($list as $user)
            {
                if(strlen($user->username) <= 2) continue;
                if ($roles = $auth->getRolesByUser($user->ID)) {
                    // it's enough for us the get first assigned role name
                    $role = array_keys($roles)[0]; 
                }

                $userId = $user->ID;
                if(empty($user->access_role))
                    $user->access_role = 'Dosen';
                
          
                if($user->save())
                {
                    $counter++;
                    $oldRole = (isset($role)) ? $auth->getRole($role) : $auth->getRole('Dosen');
                     // print_r($oldRole);exit;
                    $newRole = $auth->getRole($user->access_role);
                    if ($auth->revoke($oldRole, $userId)) {
                        $info = $auth->assign($newRole, $userId);
                    }

                    if (!isset($role)) {
                        $info = $auth->assign($newRole, $userId);
                    }
                    if (!$info) {
                        $errors .= 'There was some error while saving user role.';
                        throw new \Exception;
                        
                    }
                }

                else{
                    $errors .= \app\helpers\MyHelper::logError($user);
                    throw new \Exception;
                }
            }
            echo 'Data updated '.$counter;
            $transaction->commit();
        }

        catch(\Exception $e)
        {
            $errors .= $e->getMessage();
            $transaction->rollBack();
            echo $errors;
        }

        echo "\n";
        return ExitCode::OK;
    }

    public function actionUpdateNamaDosen()
    {
        $list = User::find()->all();
        $transaction = Yii::$app->db->beginTransaction();
            // exit;
        $errors = '';
        try 
        {
            $counter = 0;
            foreach($list as $user)
            {
                $d = $user->dataDiri;
                if(empty($d)) continue;

                if(strlen($user->NIY) <=2 ) continue;
                

                $user->nama = $d->nama;
                $user->username = $user->NIY;

                if(!empty($user->prodiUser))
                    $user->fakultas_id = $user->prodiUser->id_fak;
                
                if($user->save())
                {
                    $counter++;
                }

                else{
                    $errors .= \app\helpers\MyHelper::logError($user);
                    throw new \Exception;
                }
            }
            echo 'Data updated '.$counter;
            $transaction->commit();
        }

        catch(\Exception $e)
        {
            $errors .= $e->getMessage();
            $transaction->rollBack();
            echo $errors;
        }

        echo "\n";
        return ExitCode::OK;
    }

    public function actionUpdateNamaTendik()
    {
        $list = User::find()->all();
        $transaction = Yii::$app->db->beginTransaction();
            // exit;
        $errors = '';
        try 
        {
            $counter = 0;
            foreach($list as $user)
            {
                $d = Tendik::find()->where(['NIY' => $user->NIY])->one();
                if(empty($d)) continue;

                if(strlen($user->NIY) <=2 ) continue;
                

                $user->nama = $d->nama;
                
                if($user->save())
                {
                    $counter++;
                }

                else{
                    $errors .= \app\helpers\MyHelper::logError($user);
                    throw new \Exception;
                }
            }
            echo 'Data tendik updated '.$counter;
            $transaction->commit();
        }

        catch(\Exception $e)
        {
            $errors .= $e->getMessage();
            $transaction->rollBack();
            echo $errors;
        }

        echo "\n";
        return ExitCode::OK;
    }
    
    protected function findModel($id)
    {
        if (($model = Tendik::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

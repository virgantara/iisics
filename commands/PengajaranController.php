<?php

namespace app\commands;

use Yii;
use app\models\Prodi;
use app\models\MasterLevel;
use app\models\Pengajaran;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\web\NotFoundHttpException;
use yii\httpclient\Client;
use app\models\CatatanHarian;

/**
 * TendikController implements the CRUD actions for Tendik model.
 */
class PengajaranController extends Controller
{
    function experience($L) {
        $a=0;
        for($x=1; $x<$L; $x++) {
            $a += floor($x+300*pow(2, ($x/7)));
        }
        return floor($a/4);
    }
    public function actionGenerateLevelExp($max_level)
    {
        
        for($L=1;$L<=$max_level;$L++) {
            $level = MasterLevel::findOne($L);
            if(empty($level))
                $level = new MasterLevel;

            $exp = $this->experience($L);
            $level->level = $L;
            $level->exp = $exp;
            $level->save();
            echo 'Level '.$L.': '.$exp;
            echo "\n";
        }
        echo "\n";
        return ExitCode::OK;
    }

    public function actionImportJurnal($req, $user_id)
    {
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];
        $params = [
            'jadwal_id' => $req['id']
        ];
        $unsur = \app\models\UnsurKegiatan::findOne(1);
        $response = $client->get('/jadwal/dosen/jurnal', $params,$headers)->send();
        $errors = '';
        if ($response->isOk) 
        {

            $results = $response->data['values'];
            $status = $response->data['status'];

            if($status == 200)
            {
                foreach($results as $res)
                {
                    $kondisi = 'CH'.$req['id'].'_'.$res['id'];    
                

                    $catatan = CatatanHarian::find()->where(['kondisi' => $kondisi])->one();
                    if(empty($catatan)){
                        $catatan = new CatatanHarian;
                    }


                    $catatan->user_id = $user_id;
                    $catatan->unsur_id = $unsur->id;
                    $catatan->deskripsi = $unsur->nama.' pertemuan ke-'.$res['pertemuan_ke'].' matkul '.$req['nama_mk'].' '.$req['sks'].' di ruang '.$res['ruang'];
                    $catatan->is_selesai = '1';
                    $catatan->tanggal = date('Y-m-d',strtotime($res['waktu']));
                    if(!$catatan->save())
                    {

                        $errors .= \app\helpers\MyHelper::logError($catatan);
                        print_r($errors);exit;
                    }
                }
            }
        }

        return $errors;
    }

    public function actionImport($tahun)
    {
        $api_baseurl = Yii::$app->params['api_baseurl'];
        $client = new Client(['baseUrl' => $api_baseurl]);
        $client_token = Yii::$app->params['client_token'];
        $headers = ['x-access-token'=>$client_token];
        // $dataPost = $_POST['dataPost'];
        $results = [];

        $transaction = Yii::$app->db->beginTransaction();
        $errors = '';
        $count_sukses = 0;
        $count_failed = 0;
        $list_prodi = Prodi::find()->all();
        try 
        {
            foreach($list_prodi as $prodi)
            {
                if($prodi->jumlahDosen == 0) continue;

                foreach($prodi->dosenProdi as $q => $dosen)
                {
                    if(!empty($dosen))
                    {
                        $params = [
                            'uuid' => $dosen->uuid,
                            'tahun' => $tahun
                        ];

                        $response = $client->get('/jadwal/dosen/uuid', $params,$headers)->send();
                     
                        if ($response->isOk) 
                        {
                            $results = $response->data['values'];
                            $status = $response->data['status'];
                            if($status == 200)
                            {
                                foreach($results as $res)
                                {

                                    $model = Pengajaran::find()->where(['jadwal_id'=>$res['id']])->one();
                                    if(empty($model))
                                    {
                                        $model = new Pengajaran;
                                        $model->jadwal_id = $res['id'];
                                        $model->NIY = $dosen->NIY;
                                    }

                                    $model->matkul = $res['nama_mk'];
                                    $model->kode_mk = $res['kode_mk'];
                                    $model->jurusan = $res['prodi'];
                                    $model->jam = $res['jam'];
                                    $model->hari = $res['hari'];
                                    $model->kelas = $res['kelas'];
                                    $model->sks = $res['sks'];
                                    $model->tahun_akademik = $res['ta'];
                                    $model->ver = 'Sudah Diverifikasi';

                                    if($model->save())
                                    {
                                        $count_sukses++;

                                        // $err = $this->actionImportJurnal($res,$dosen->ID);
                                        // if(isset($err)){
                                        //     $errors .= 'oops'.$err;
                                        //     throw new \Exception;
                                            
                                        // }
                                    }

                                    else
                                    {
                                        
                                        foreach($model->getErrors() as $attribute){
                                            foreach($attribute as $error){
                                                $errors .= $error.' ';
                                            }
                                        }

                                        throw new \Exception;
                                        
                                    }


                                }    
                            }
                            
                        }    
                    }
                    
                    else{
                        $count_failed++;
                    }
                }
            }
            $transaction->commit();
            $results = [
                'status' => 200,
                'message' => $count_sukses.' Data synced'
            ];
        }

        catch(\Exception $e)
        {
            $errors .= $e->getMessage();
            $results = [
                'status' => 500,
                'message' => $errors
            ];
            $transaction->rollback();
        }

        print_r($results);
        echo "\n";
        return ExitCode::OK;
 
    }

    public function actionSyncAccount()
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


    /**
     * Finds the Tendik model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tendik the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tendik::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

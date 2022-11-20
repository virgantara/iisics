<?php
namespace app\helpers;

use Yii;
use yii\helpers\Json;
use yii\httpclient\Client;


if (!defined('STDIN')) {
  define('STDIN', fopen('php://stdin', 'r'));
}

class FeederHelper
{	

   

    
    public static function getListRiwayatPendidikanMahasiswa($nim)
    {

        $result = [];

        $feeder_baseurl = Yii::$app->params['feeder']['baseurl'];
        $feeder_username = Yii::$app->params['feeder']['username'];
        $feeder_password = Yii::$app->params['feeder']['password'];

        $client = new \GuzzleHttp\Client([
            'base_uri' => $feeder_baseurl   
        ]);
        $headers = ['Content-Type'=>'application/json'];
        refreshDetail:
        $token = FeederHelper::getFeederToken();
        
        $params = [
            'act' => 'GetListRiwayatPendidikanMahasiswa',
            'token'   => $token,
            'filter' => "nim = '".$nim."' ",
           
        ];

        $response = $client->request('POST', '/ws/live2.php',[
            'headers' => $headers,
            'body' => json_encode($params)
        ]);
        
        $resp = $response->getBody()->getContents();
        
        $resp = json_decode($resp);

        if($resp->error_code == 0) {
            $hasils = $resp->data;

            if(count($hasils) > 0) {
                $tmp = $hasils[0];        
                $id_reg_pd = $tmp->id_registrasi_mahasiswa;
                $result = [
                    'code' => 200,
                    'message' => 'Ok',
                    'id_reg_pd' => $id_reg_pd
                ];
            }

            else{
                $result = [
                    'code' => 404,
                    'message' => 'ID Reg Mhs ini tidak ada FEEDER',
                    'id_reg_pd' => null
                ];
            }
        }

        else if($resp->error_code == 100){
            FeederHelper::wsFeederLogin();
            goto refreshDetail;
        }

        else{
            $errors = 'Error: '.$resp->error_code.' - '.$resp->error_desc;
            $result = [
                'code' => 500,
                'message' => $errors,
                'id_reg_pd' => null
            ];
        }

        return $result;
    }

   

   
    public static function getFeederToken()
    {
        $feederToken = '';
        $tokenPath = Yii::getAlias('@webroot').'/credentials/feeder_token.json';
           
        if (file_exists($tokenPath)) 
        {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $feederToken = $accessToken['id_token'];

            
        }

        else{
            if(!FeederHelper::wsFeederLogin()){
                throw new \Exception("Error Creating FEEDER Token", 1);
            }

            else {
                $accessToken = json_decode(file_get_contents($tokenPath), true);
                $feederToken = $accessToken['id_token'];
            }
        }

        return $feederToken;
    }

    public static function wsFeederLogin()
    {
        $feeder_baseurl = Yii::$app->params['feeder']['baseurl'];
        $feeder_username = Yii::$app->params['feeder']['username'];
        $feeder_password = Yii::$app->params['feeder']['password'];
        
        $client = new \GuzzleHttp\Client([
            'base_uri' => $feeder_baseurl
            
        ]);
        $token = '';
        $errors = '';
        try 
        {
            $headers = ['Content-Type'=>'application/json'];
            
            $params = [
                'act' => 'GetToken',
                'username'   => $feeder_username,
                'password'   => $feeder_password,
            ];


            $response = $client->request('POST', '/ws/live2.php',[
                'headers' => $headers,
                'body' => json_encode($params)
            ]);
           
            $results = $response->getBody()->getContents();
            $results = json_decode($results);
            if($results->error_code == 0)
            {
                $data = [
                    'id_token' => $results->data->token,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $tokenPath = Yii::getAlias('@webroot').'/credentials/feeder_token.json';
         
                
                file_put_contents($tokenPath, json_encode($data));
            }

            else
            {
                $errors .= $results->error_desc;
                throw new \Exception;
            }

            return true;
        }   

        catch(\Exception $e)
        {
            $errors .= $e->getMessage();
            print_r($errors);exit;
            return false;

        }
        
        
    }
}
<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;

use app\models\Jadwal;
use app\models\Kehadiran;
use app\models\KehadiranRekap;
use app\helpers\MyHelper;
use app\models\User;
use app\models\UnitKerja;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class KehadiranController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionTarik($uid=null, $sd = null, $ed = null)
    {
        try{

            
            setlocale(LC_TIME, 'id_ID.utf8');

            if(empty($sd) && empty($ed)){
                $error = "Maaf, tanggal tidak boleh kosong. Expected sd (start date) & ed (end date)\n";
                echo "\n";
                $log  = "Error: ".$error.PHP_EOL.
                        "-------------------------".PHP_EOL;

                file_put_contents('./logs/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
                return ExitCode::DATAERR;
            }

            $sd = date('Y-m-d 00:00:00',strtotime($sd));
            $ed = date('Y-m-d 23:59:59',strtotime($ed));
            
            $interval = \app\helpers\MyHelper::hitungSelisihHari($sd,$ed);
            $jumlah_hari = $interval->days;    
            
            if($jumlah_hari > 7){
                $error = "Maaf, tanggal tidak boleh lebih dari 7 hari\n";
                echo "\n";
                $log  = "Error: ".$error.PHP_EOL.
                        "-------------------------".PHP_EOL;

                file_put_contents('./logs/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
                return ExitCode::DATAERR;
            }        

            $period = new \DatePeriod(
                 new \DateTime(date('Y-m-d',strtotime($sd))),
                 new \DateInterval('P1D'),
                 new \DateTime(date('Y-m-d',strtotime($ed))." +1 day")
            );

            

            $list_niy = [];
            $query = UnitKerja::find();
            if(!empty($uid)){
                $query->andWhere(['id'=>$uid]);                
            }

            else{
                $query->andWhere(['id'=>'-']);
            }

            $satkers = $query->all();
            
            foreach($satkers as $parent) {

                foreach($parent->jabatans as $jab) {
                    if(!in_array($jab->NIY,$list_niy) && $jab->nIY->status == 'aktif'){
                        $list_niy[] = $jab->NIY;   
                    }
                }
                
                foreach($parent->unitKerjas as $satker){
                    foreach($satker->jabatans as $jab) {
                        if(!in_array($jab->NIY,$list_niy) && $jab->nIY->status == 'aktif'){
                            $list_niy[] = $jab->NIY;   
                        }
                    }
                }
            }


            $list = Jadwal::find()->all();

            $list_jadwal = [];
            foreach($list as $j){
                $list_jadwal[strtolower($j->hari)] = $j->attributes;
            }


            foreach($list_niy as $niy){
                
                            

                // get data per hari
                foreach ($period as $key => $value) {
                    
                    $ssd = $value->format('Y-m-d 00:00:00');
                    $eed = $value->format('Y-m-d 23:59:59');

                    $hari_ini = new \DateTime($value->format('Y-m-d'));
                    $hari_ini = strftime('%A', $hari_ini->getTimestamp());
                    
                    $hari_jadwal = $list_jadwal[strtolower($hari_ini)];

                    $rekap = KehadiranRekap::findOne([
                        'niy' => $niy,
                        'tanggal' => $value->format('Y-m-d')
                    ]);

                    if(empty($rekap)){
                        $rekap = new KehadiranRekap;
                        $rekap->id = MyHelper::gen_uuid();
                        $rekap->tanggal = $value->format('Y-m-d');
                        $rekap->niy = $niy;
                    }

                    $rekap->status_sync = 'sync';
                    $rekap->status_kehadiran = $hari_jadwal['jenis_hari'] == '1' ? '4' : '5';
                    $rekap->keterangan = $hari_jadwal['jenis_hari'] == '1' ? 'Tidak hadir' : 'Libur';
                    
                    // if($niy == '060198'){
                    //     print_r($rekap);exit;
                    // }
                    if(!$rekap->save()){
                        $error = MyHelper::logError($rekap);
                        echo "\n";
                        $log  = "NIY: ".$niy.' - '.date("F j, Y, g:i a").PHP_EOL.
                                "Error: ".$error.PHP_EOL.
                                "-------------------------".PHP_EOL;

                        file_put_contents('./logs/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
                        return ExitCode::DATAERR;
                    }


                    $query = Kehadiran::find();
                    $query->andWhere(['niy' => $niy]);
                    $query->andFilterWhere(['between','tanggal_checkin',$ssd, $eed]);
                    $query->orderBy(['tanggal_checkin' => SORT_ASC]);

                    $list_kehadiran = $query->asArray()->all();
                    $rekap = KehadiranRekap::findOne([
                        'niy' => $niy,
                        'tanggal' => $value->format('Y-m-d')
                    ]);

                    if($hari_jadwal['jenis_hari'] == '1' && count($list_kehadiran) > 1){

                        $check_awal = $list_kehadiran[0];
                        $check_akhir = $list_kehadiran[count($list_kehadiran)-1];
                        
                        if(!empty($rekap)){
                            $rekap->jam_hadir = $check_awal['tanggal_checkin'];
                            $rekap->jam_pulang = $check_akhir['tanggal_checkin'];
                            $rekap->status_kehadiran = '1';
                            $rekap->keterangan = 'Hadir';
                                


                            if(date('H:i:s',strtotime($rekap->jam_hadir)) > date('H:i:s',strtotime($hari_jadwal['jam_terlambat']))){

                                $waktu_hadir = date('H:i:s',strtotime($rekap->jam_hadir));

                                $interval = \app\helpers\MyHelper::hitungSelisihWaktu(
                                    $hari_jadwal['jam_terlambat'],
                                    $waktu_hadir                                
                                );

                                $rekap->keterangan = 'Terlambat '.$interval;
                                $rekap->menit_terlambat = (strtotime($waktu_hadir) - strtotime($hari_jadwal['jam_terlambat'])) / 60;
                            }

                            if(date('H:i:s',strtotime($check_akhir['tanggal_checkin'])) < date('H:i:s',strtotime($hari_jadwal['jam_pulang'])) && date('H:i:s',strtotime($check_akhir['tanggal_checkin'])) >= date('H:i:s',strtotime('13:00:00'))){
                                $rekap->keterangan = 'Pulang awal';
                            }

                            if($check_awal['status_kehadiran'] != '1'){
                                $rekap->status_kehadiran = $check_awal['status_kehadiran'];
                                $rekap->keterangan = $check_awal['keterangan'];
                            }


                            if(!$rekap->save()){
                                $error = MyHelper::logError($rekap);
                                echo "\n";
                                $log  = "NIY: ".$niy.' - '.date("F j, Y, g:i a").PHP_EOL.
                                        "Error: ".$error.PHP_EOL.
                                        "-------------------------".PHP_EOL;

                                file_put_contents('./logs/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
                                return ExitCode::DATAERR;
                            }

                            echo $niy." ".$check_awal['tanggal_checkin']." Data updated\n";
                        }
                        
                    }

                    else if($hari_jadwal['jenis_hari'] == '1' && count($list_kehadiran) == 1){
                        if(!empty($rekap)){
                            $check_awal = $list_kehadiran[0];
                            $check_akhir = $list_kehadiran[count($list_kehadiran)-1];
                            $rekap->jam_hadir = $check_awal['tanggal_checkin'];
                            $rekap->jam_pulang = null;
                            $rekap->keterangan = 'Hadir';
                            $rekap->status_kehadiran = '1';

                            if(date('H:i:s',strtotime($rekap->jam_hadir)) > date('H:i:s',strtotime($hari_jadwal['jam_terlambat']))){

                                $waktu_hadir = date('H:i:s',strtotime($rekap->jam_hadir));

                                $interval = \app\helpers\MyHelper::hitungSelisihWaktu(
                                    $hari_jadwal['jam_terlambat'],
                                    $waktu_hadir                                
                                );

                                $rekap->keterangan = 'Terlambat '.$interval;
                                $rekap->menit_terlambat = (strtotime($waktu_hadir) - strtotime($hari_jadwal['jam_terlambat'])) / 60;
                            }

                            if($check_awal['status_kehadiran'] != '1'){
                                $rekap->status_kehadiran = $check_awal['status_kehadiran'];
                                $rekap->keterangan = $check_awal['keterangan'];
                            }

                            if(!$rekap->save()){
                                $error = MyHelper::logError($rekap);
                                echo "\n";
                                $log  = "NIY: ".$niy.' - '.date("F j, Y, g:i a").PHP_EOL.
                                        "Error: ".$error.PHP_EOL.
                                        "-------------------------".PHP_EOL;

                                file_put_contents('./logs/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
                                return ExitCode::DATAERR;
                            }
                            echo $niy." ".$check_awal['tanggal_checkin']." Data updated\n";
                        }
                    }

                    
                }
                
            }
        }

        catch(\Exception $e){
            $error = $e->getMessage().' Line: '.$e->getLine();
            echo "\n";
            $log  = "Error: ".$error.PHP_EOL.
                    "-------------------------".PHP_EOL;

            file_put_contents('./logs/log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
            return ExitCode::DATAERR;
        }
        

        return ExitCode::OK;
    }
}

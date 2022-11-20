<?php

namespace app\commands;

use Yii;
use app\helpers\MyHelper;
use app\models\User;
use app\models\Buku;
use app\models\PengabdianLuaranAuthors;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\web\NotFoundHttpException;


/**
 * TendikController implements the CRUD actions for Tendik model.
 */
class LuaranController extends Controller
{

    public function actionUpdateBukuAuthor()
    {
        $list = Buku::find()->all();
        $transaction = Yii::$app->db->beginTransaction();
            // exit;
        $errors = '';
        try 
        {
            $counter = 0;
            foreach($list as $buku)
            {
                if(empty($buku->NIY)) continue;
                $author = PengabdianLuaranAuthors::find()->where([
                    'pengabdian_luaran_id' => $buku->uuid,
                    'NIY' => $buku->NIY,
                    'jenis' => 'buku'
                ])->one();

                if(empty($author)){
                    $author = new PengabdianLuaranAuthors;
                    $author->jenis = 'buku';
                    $author->pengabdian_luaran_id = $buku->uuid;
                    $author->NIY = $buku->NIY;
                    $author->id = MyHelper::gen_uuid();
                }
                
                if($author->save())
                {
                    $counter++;
                }

                else{
                    $errors .= \app\helpers\MyHelper::logError($author);
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

    public function actionUpdateBukuUuid()
    {
        $list = Buku::find()->all();
        $transaction = Yii::$app->db->beginTransaction();
            // exit;
        $errors = '';
        try 
        {
            $counter = 0;
            foreach($list as $buku)
            {
                if(!empty($buku->uuid)) continue;

                $buku->uuid = MyHelper::gen_uuid();

                if(empty($buku->halaman))
                    $buku->halaman = 1;

                if($buku->save())
                {
                    $counter++;
                }

                else{
                    $errors .= \app\helpers\MyHelper::logError($buku);
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
    
    protected function findModel($id)
    {
        if (($model = Tendik::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

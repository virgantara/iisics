<?php
namespace app\controllers;

use Yii;

use app\helpers\MyHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use app\models\Abstracts;
use app\models\AbstractReview;
use app\models\Papers;
use app\models\PaperReview;


use \Firebase\JWT\JWT;
use app\models\Menu;
use yii\httpclient\Client;

/**
 * Site controller
 */
class AjaxController extends Controller
{
    public function actionAjaxCountAbstract()
    {
        $query = (new \yii\db\Query())
            ->select(['COUNT(*) as total'])
            ->from('abstracts');
        $submitted = $query->one();

        $query = (new \yii\db\Query())
            ->select(['COUNT(*) as total'])
            ->from('abstracts')
            ->where(['abs_status' => 'None']);
        $waiting = $query->one();

        $query = (new \yii\db\Query())
            ->select(['COUNT(*) as total'])
            ->from('abstracts')
            ->where(['abs_status' => 'Accepted']);
        $accepted = $query->one();

        $query = (new \yii\db\Query())
            ->select(['COUNT(*) as total'])
            ->from('abstracts')
            ->where(['abs_status' => 'Rejected']);
        $rejected = $query->one();

        $results = [
            'code' => 200,
            'message' => 'Success',
            'submitted' => $submitted,
            'waiting' => $waiting,
            'accepted' => $accepted,
            'rejected' => $rejected
        ];

        echo json_encode($results);
        exit;
    }

    public function actionAjaxCountPaper()
    {
        $query = (new \yii\db\Query())
            ->select(['COUNT(*) as total'])
            ->from('papers');
        $submitted = $query->one();

        $query = (new \yii\db\Query())
            ->select(['COUNT(*) as total'])
            ->from('papers')
            ->where(['paper_status' => 'None']);
        $waiting = $query->one();

        $query = (new \yii\db\Query())
            ->select(['COUNT(*) as total'])
            ->from('papers')
            ->where(['paper_status' => 'Accepted']);
        $accepted = $query->one();

        $query = (new \yii\db\Query())
            ->select(['COUNT(*) as total'])
            ->from('papers')
            ->where(['paper_status' => 'Rejected']);
        $rejected = $query->one();

        $results = [
            'code' => 200,
            'message' => 'Success',
            'submitted' => $submitted,
            'waiting' => $waiting,
            'accepted' => $accepted,
            'rejected' => $rejected
        ];

        echo json_encode($results);
        exit;
    }

    public function actionAjaxCountTopicAbstract()
    {
        $query = (new \yii\db\Query())
            ->select(['t.topic_title', 'count(*) as total'])
            ->from('abstracts a')
            ->join('JOIN','topics t', 't.topic_id = a.topic_id')
            ->groupBy(['t.topic_title']);
        $items = $query->all();

        $results = [
            'code' => 200,
            'message' => 'Success',
            'items' => $items,
        ];

        echo json_encode($results);
        exit;
    }

}

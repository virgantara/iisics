<?php
use app\helpers\MyHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AbstractsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Abstracts';
$this->params['breadcrumbs'][] = $this->title;

$list_topic = \app\models\Topics::find()->all();
$list_status_abstrak = MyHelper::statusAbstract();
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
<div class="panel-body ">

    <?php
    $gridColumns = [
    [
        'class'=>'kartik\grid\SerialColumn',
        'contentOptions'=>['class'=>'kartik-sheet-style'],
        'width'=>'36px',
        'pageSummary'=>'Total',
        'pageSummaryOptions' => ['colspan' => 6],
        'header'=>'',
        'headerOptions'=>['class'=>'kartik-sheet-style']
    ],

            'abs_title',
            'abs_author',
            'abs_institution',
            [
                'attribute' => 'topic_id',
                'filter' => ArrayHelper::map($list_topic,'topic_id','topic_title'),
                'value' => function($data){
                    return (!empty($data->topic) ? $data->topic->topic_title : null);
                }
            ],
            //'abs_abstract:ntext',
            //'abs_keyword',
            //'abs_type',
            [
                'attribute' => 'abs_status',
                'filter' => $list_status_abstrak,
                'format' => 'raw',
                'value' => function($data) use ($list_status_abstrak) {
                    if($data->abs_status == 'Accepted'){
                        return '<span class="label label-success arrowed">'.$data->abs_status.'</span>';
                    }

                    else if($data->abs_status == 'Rejected'){
                        return '<span class="label label-danger arrowed-in">'.$data->abs_status.'</span>';
                    }
                }
            ],
            //'abs_paid',
            'presenter_name',
            'abs_date',
            //'examiner_by',
            //'rev_id',
            //'rev_name',
            //'viewed',
    ['class' => 'yii\grid\ActionColumn']
];?>    
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'containerOptions' => ['style' => 'overflow: auto'], 
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'containerOptions' => ['style'=>'overflow: auto'], 
        'beforeHeader'=>[
            [
                'columns'=>[
                    ['content'=> $this->title, 'options'=>['colspan'=>14, 'class'=>'text-center warning']], //cuma satu 
                ], 
                'options'=>['class'=>'skip-export'] 
            ]
        ],
        'exportConfig' => [
              GridView::PDF => ['label' => 'Save as PDF'],
              GridView::EXCEL => ['label' => 'Save as EXCEL'], //untuk menghidupkan button export ke Excell
              GridView::HTML => ['label' => 'Save as HTML'], //untuk menghidupkan button export ke HTML
              GridView::CSV => ['label' => 'Save as CSV'], //untuk menghidupkan button export ke CVS
          ],
          
        'toolbar' =>  [
            '{export}', 

           '{toggleData}' //uncoment untuk menghidupkan button menampilkan semua data..
        ],
        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
    // set export properties
        'export' => [
            'fontAwesome' => true
        ],
        'pjax' => true,
        'bordered' => true,
        'striped' => true,
        // 'condensed' => false,
        // 'responsive' => false,
        'hover' => true,
        // 'floatHeader' => true,
        // 'showPageSummary' => true, //true untuk menjumlahkan nilai di suatu kolom, kebetulan pada contoh tidak ada angka.
        'panel' => [
            'type' => GridView::TYPE_PRIMARY
        ],
    ]); ?>

</div>
        </div>
    </div>

</div>


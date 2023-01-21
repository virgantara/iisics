<?php
use app\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PapersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Papers';
$this->params['breadcrumbs'][] = $this->title;

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
            [
                'attribute' => 'pid',
                // 'contentOptions' => ['width' => '10%'],
                'value' => function($data){
                    return (!empty($data->p) ? $data->p->name : null);
                }
            ],
            [
                'attribute' => 'abs_id',
                'contentOptions' => ['width' => '25%'],
                'value' => function($data){
                    return (!empty($data->abs) ? $data->abs->abs_title : null);
                }
            ],
            [
                'attribute' => 'paper_info',
                // 'contentOptions' => ['width' => '20%']
            ],
            [
                'attribute' => 'paper_status',
                'filter' => $list_status_abstrak,
                'format' => 'raw',
                'value' => function($data) use ($list_status_abstrak) {
                    if($data->paper_status == 'Accepted'){
                        return '<span class="label label-success arrowed">'.$data->paper_status.'</span>';
                    }

                    else if($data->paper_status == 'Rejected'){
                        return '<span class="label label-danger arrowed-in">'.$data->paper_status.'</span>';
                    }
                }
            ],
            //'paper_ext',
            //'paper_date',
            [
                'attribute' => 'paper_file',
                'format' => 'raw',
                'value' => function($data){
                    if(!empty($data->paper_file))
                        return Html::a('<i class="fa fa-download"></i> Download',['papers/download','id' => $data->paper_id],['class' => 'btn btn-primary','target'=>'_blank','data-pjax'=>0]);
                    else{
                        return '<span style="color:red">Not uploaded</span>';
                    }
                }
            ],
            //'paper_editor_comment:ntext',
            //'paper_final',
            //'paper_reviewed',
            //'paper_recomendation',
            //'paper_review_comment:ntext',
            //'paper_review_date',
            //'paper_review_file',
            //'paper_review_file_raw',
            //'paper_review_file_ext',
            //'paper_revised_file',
            //'paper_revised_file_raw',
            //'paper_revised_file_ext',
            //'paper_final_file',
            //'paper_final_file_raw',
            //'paper_final_file_ext',
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


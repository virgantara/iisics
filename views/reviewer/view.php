<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
$list_topic = \app\models\Topics::find()->all();
/* @var $this yii\web\View */
/* @var $model app\models\Reviewer */

$this->title = $model->rev_name;
$this->params['breadcrumbs'][] = ['label' => 'Reviewers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-header">
    <h2><?= Html::encode($this->title) ?></h2>
</div>
<div class="row">
   <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <?= Html::a('Update', ['update', 'id' => $model->rev_id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->rev_id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>

            <div class="panel-body ">
                        
                <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            // 'rev_id',
                            'rev_email:email',
                            // 'rev_password',
                            'rev_type',
                            'rev_name',
                            'rev_status',
                            // 'rev_enc',
                        ],
                    ]) ?>

            </div>
        </div>

    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Abstract Reviews</h3>
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
                            'header' => 'Title',
                            'value' => function($data){
                                return (!empty($data->abs) ? $data->abs->abs_title : null);
                            }
                        ],
                        
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




<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Paper Reviews</h3>
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
                            'header' => 'Title',
                            'value' => function($data){
                                return (!empty($data->abs) ? $data->abs->abs_title : null);
                            }
                        ],
                        [
                            'header' => 'Topic',
                            'filter' => ArrayHelper::map($list_topic,'topic_id','topic_title'),
                            'value' => function($data){
                                return (!empty($data->abs->topic) ? $data->abs->topic->topic_title : null);
                            }
                        ],
                        [
                            'header' => 'Abstract',
                            'format' => 'raw',
                            'contentOptions' => ['width'=>'50%'],
                            'value' => function($data){
                                return (!empty($data->abs) ? $data->abs->abs_abstract : null);
                            }
                        ],
                        
                ['class' => 'yii\grid\ActionColumn']
            ];?>    
            <?= GridView::widget([
                    'dataProvider' => $dataProviderPaperReview,
                    'filterModel' => $searchModelPaperReview,
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


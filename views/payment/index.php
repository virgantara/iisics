<?php
use app\helpers\MyHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payments';
$this->params['breadcrumbs'][] = $this->title;

$list_payment_method = MyHelper::paymentMethod();
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
                'attribute' => 'pay_file',
                'format' => 'raw',
                'value' => function($data){
                    if(!empty($data->pay_file)){
                        // // return Html::img(Url::to(['simak-mastermahasiswa/foto','id'=>$data->id]),['width'=>'70px']);
                        // return Html::a(Html::img($data->foto_path,['width'=>'70px']),'',['class'=>'popupModal','data-pjax'=>0,'data-item'=>$data->foto_path]);
                        return Html::a(Html::img(Url::to(['payment/foto','id'=>$data->pay_id]),['width'=>'120px']),'',['class'=>'popupModal','data-pjax'=>0,'data-item'=>Url::to(['payment/foto','id'=>$data->pay_id])]);
                    }
                        
                    else
                        return '';
                }
            ],
            [
                'attribute' => 'pid',
                'contentOptions' => ['width' => '15%'],
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
            // 'pay_created',
            'pay_date',
            //'pay_file',
            [
                'attribute' => 'pay_method',
                'filter' => $list_payment_method,
            ],
            'pay_origin',
            'pay_destination',
            //'pay_currency',
            
            [
                'attribute' => 'pay_nominal',
                'contentOptions' => ['class' => 'text-right'],
                'value' => function($data){
                    return MyHelper::formatRupiah($data->pay_nominal,2);
                }
            ],
            //'pay_info:ntext',
            //'pay_status',
            //'valid_by',
            //'valid_by_name',
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


<?php
        yii\bootstrap\Modal::begin(['id' =>'modal','size'=>'modal-lg',]);
        echo '<div class="text-center">';
        echo '<img width="100%" id="img">';
        echo '</div>';
        yii\bootstrap\Modal::end();
    ?>
<?php

$this->registerJs("


$(document).on('click','.popupModal',function(e){
    e.preventDefault();
    var m = $('#modal').modal('show').find('#img');

    m.attr('src',$(this).data('item'))
})
");
?>
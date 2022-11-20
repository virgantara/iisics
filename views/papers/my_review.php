<?php
use app\helpers\MyHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\grid\GridView;


$list_status = MyHelper::reviewerResultStatus();

/* @var $this yii\web\View */
/* @var $model app\models\Abstracts */

$this->title = 'Paper Review';
$this->params['breadcrumbs'][] = ['label' => 'Abstract', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Paper Review Results</h3>
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
                    'attribute' => 'rev_id',
                    'value' => function($data){
                        return (!empty($data->rev) ? $data->rev->rev_name : null);
                    },
                    // 'visible' => Yii::$app->user->can('admin')
                ],
                [
                    'attribute' => 'abs_id',
                    'value' => function($data){
                        return (!empty($data->abs) ? $data->abs->abs_title : null);
                    },
                    // 'visible' => Yii::$app->user->can('admin')
                ],
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'comment_from_reviewer',
                    'contentOptions' => ['width' => '30%'],
                    'readonly' => !Yii::$app->user->can('reviewer') && !Yii::$app->user->can('admin'),
                    'editableOptions' => [
                        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                        
                    ],
                ],
                [
                    'attribute' => 'response_from_author',
                    'contentOptions' => ['width' => '30%'],
                ],
                [
                    'attribute' => 'acceptance_status',
                    'filter' => $list_status,
                ],
                [
                    'attribute' => 'file_path',
                    'format' => 'raw',
                    'value' => function($data){
                        if(!empty($data->file_path))
                            return Html::a('<i class="fa fa-download"></i> Download',['paper-review/download','id' => $data->id],['class' => 'btn btn-primary','target'=>'_blank','data-pjax'=>0]);
                        else{
                            return '<span style="color:red">Not uploaded</span>';
                        }
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} ',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if($action == 'update') {
                            return Url::to(['paper-review/update','id'=>$model->id]); 
                        }
                        else if($action == 'delete') {
                            return Url::to(['paper-review/delete','id'=>$model->id]); 
                        }
                        else {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }   
                    },
                ]
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
                    'pjaxSettings' =>[
                        'neverTimeout'=>true,
                        'options'=>[
                            'id'=>'pjax-container',
                        ]
                    ],  
                    'id' => 'grid-reviewer',
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

$this->registerJs(' 


$(document).on("click", "#btn-simpan", function(e){
    e.preventDefault();
    
    var obj = $("#form-reviewer").serialize()
    
    $.ajax({
        url: "/abstract-review/ajax-add",
        type : "POST",
        async : true,
        data: obj,
        error : function(e){
            console.log(e.responseText)
        },
        beforeSend: function(){
            Swal.fire({
                title : "Please wait",
                showConfirmButton: false,
                html: "Sending email to the reviewer...",      
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                
            })
        },
        success: function (data) {
            Swal.close()
            $("#modal").modal("hide")
            var hasil = $.parseJSON(data)
            if(hasil.code == 200){
                Swal.fire({
                    title: \'Yeay!\',
                    icon: \'success\',
                    text: hasil.message
                });
                
                $.pjax.reload({container: "#pjax-container"});
            }

            else{
                Swal.fire({
                    title: \'Oops!\',
                    icon: \'error\',
                    text: hasil.message
                })
            }
        }
    })
});

$(document).on("click", "#btn-add", function(e){
    e.preventDefault();
    $("#modal").modal("show")
    
});



', \yii\web\View::POS_READY);

?>
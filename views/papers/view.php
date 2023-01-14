<?php
use app\helpers\MyHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Papers */

$this->title = (!empty($model->abs) ? $model->abs->abs_title : '');
$this->params['breadcrumbs'][] = ['label' => 'Papers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$list_reviewer = ArrayHelper::map(\app\models\Reviewer::find()->where(['rev_status'=>'active'])->orderBy(['rev_name' =>SORT_ASC])->all(),'rev_id','rev_name');

$list_status_abstrak = MyHelper::statusAbstract();
?>
<div class="block-header">
    <h2><?= Html::encode($this->title) ?></h2>
</div>
<div class="row">
    <div class="col-md-12">
    <?php 
    if($model->paper_status == 'None'){
     ?>
    
    <?= Html::a('Update', ['update', 'id' => $model->paper_id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Delete', ['delete', 'id' => $model->paper_id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
        ],
    ]) ?>
    <?php 
    }


    if(Yii::$app->user->can('admin')){
     ?>
    
    <?= Html::a('Change Acceptance Status', '#', [
        'class' => 'btn btn-success',
        'id' => 'btn-change'
        
    ]) ?>
    <?php } ?>


    </div>
</div>
<div class="row">
   <div class="col-md-6">
        <div class="panel">

            <div class="panel-body ">
        
                <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'paper_id',
                            [
                                'attribute' => 'abs_id',
                                'value' => function($data){
                                    return (!empty($data->abs) ? $data->abs->abs_title : null);
                                }
                            ],
                            [
                                'attribute' => 'pid',
                                'value' => function($data){
                                    return (!empty($data->p) ? $data->p->name : null);
                                }
                            ],
                            
                            'paper_date',
                            'paper_info:html',
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
                        ],
                    ]) ?>

            </div>
        </div>

    </div>
     <div class="col-md-6">
        <div class="panel">

            <div class="panel-body ">
        
                <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'paper_status',
                            'paper_recomendation',
                            [
                                'label' => 'Paper Latest Revision',
                                'format' => 'raw',
                                'value' => function($data){
                                    if(!empty($data->latestPaperRevision) && !empty($data->latestPaperRevision->paper_file))
                                        return Html::a('<i class="fa fa-download"></i> Download Latest Revision',['paper-revision/download','id' => $data->latestPaperRevision->id],['class' => 'btn btn-success','target'=>'_blank','data-pjax'=>0]);
                                    else{
                                        return '<span style="color:red">None is uploaded yet</span>';
                                    }
                                        
                                }
                            ],
                            // 'paper_final_file',

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
                <h3 class="panel-title">Paper Review Results</h3>

            </div>
            <div class="panel-body ">
                <?php 
                if(Yii::$app->user->can('participant')){
                    echo Html::a('<i class="fa fa-upload"></i> Upload Paper Revision', ['paper-revision/create', 'paper_id' => $model->paper_id], ['class' => 'btn btn-primary']) ;
                }
 
                
                 ?>
                <?php 
                if(Yii::$app->user->can('admin')){
                 ?>
                
                <p>
                    <?= Html::a('<i class="fa fa-user"></i> Assign Reviewer', '#', ['class' => 'btn btn-success','id'=>'btn-add']) ?>
                </p>
                <?php
                }
                $gridColumns = [
                [

                    'class'=>'kartik\grid\SerialColumn',
                    'contentOptions'=>['class'=>'kartik-sheet-style'],
                    'width'=>'36px',
                    'pageSummary'=>'Total',
                    'pageSummaryOptions' => ['colspan' => 6],
                    'header'=>'Rev No.',
                    'headerOptions'=>['class'=>'kartik-sheet-style']
                ],
                [
                    'attribute' => 'rev_id',
                    'value' => function($data){
                        return (!empty($data->rev) ? $data->rev->rev_name : null);
                    },
                    'visible' => Yii::$app->user->can('admin')
                ],

                [
                    'attribute' => 'comment_from_reviewer',
                    'contentOptions' => ['width' => '35%'],
                    'format' => 'raw',
                ],
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'response_from_author',
                    'format' => 'raw',
                    'contentOptions' => ['width' => '25%'],
                    'readonly' => !Yii::$app->user->can('participant') && !Yii::$app->user->can('admin'),
                    'editableOptions' => [
                        'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                        
                    ],
                ],
                'status',
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
                    'template' => '{delete}',
                    'visible' => Yii::$app->user->can('admin'),
                    'urlCreator' => function ($action, $model, $key, $index) {
                        
                        if($action == 'delete') {
                            return Url::to(['abstract-review/delete','id'=>$model->id]); 
                        }
                        else {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }   
                    },
                    'visibleButtons' => [
            
                        'view' => function ($model, $key, $index) {
                            return $model->access_role == 'admin';
                        },
                        'delete' => function ($model, $key, $index) {
                            return Yii::$app->user->can('admin');
                        },
                        'update' => function ($model, $key, $index) {
                            return $model->access_role == 'admin';
                        },
                    ]
                ]
            ];?>    
            <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    // 'filterModel' => $searchModel,
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


yii\bootstrap\Modal::begin([
'headerOptions' => ['id' => 'modalHeader'],
'id' => 'modal',
'size' => 'modal-lg',
'clientOptions' => ['backdrop' => 'static', 'keyboard' => true]
]);
?>
<form action="" id="form-reviewer">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="">Reviewer <span style="color:red">*</span></label>
                <?=Html::dropDownList('rev_id','',$list_reviewer,['class'=>'form-control'])?>
                <?=Html::hiddenInput('paper_id',$model->paper_id,['id'=>'paper_id'])?>
                <?=Html::hiddenInput('abs_id',$model->abs_id,['id'=>'abs_id'])?>
            </div>
            
        </div>
        
        <div class="col-md-12">
            <div class="form-group">

                <?=Html::button('<i class="fa fa-save"></i> Assign Now',['class' => 'btn btn-success btn-block btn-lg','id'=>'btn-simpan'])?>
            </div>
        </div>
    </div>
</form>
<?php
yii\bootstrap\Modal::end();
?>


<?php


yii\bootstrap\Modal::begin([
'headerOptions' => ['id' => 'modalHeaderChange'],
'id' => 'modal_change',
'size' => 'modal-lg',
'clientOptions' => ['backdrop' => 'static', 'keyboard' => true]
]);
?>
<form action="" id="form-acceptance">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="">Acceptance Status <span style="color:red">*</span></label>
                <?=Html::dropDownList('paper_status','',$list_status_abstrak,['class'=>'form-control'])?>
                <?=Html::hiddenInput('paper_id',$model->paper_id,['id'=>'paper_id'])?>
            </div>
            
        </div>
        
        <div class="col-md-12">
            <div class="form-group">

                <?=Html::button('<i class="fa fa-save"></i> Update Now',['class' => 'btn btn-success btn-block btn-lg','id'=>'btn-simpan-change'])?>
            </div>
        </div>
    </div>
</form>
<?php
yii\bootstrap\Modal::end();
?>

<?php 

$this->registerJs(' 


$(document).on("click", "#btn-simpan", function(e){
    e.preventDefault();
    
    var obj = $("#form-reviewer").serialize()
    
    $.ajax({
        url: "/paper-review/ajax-add",
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


$(document).on("click", "#btn-simpan-change", function(e){
    e.preventDefault();
    
    var obj = $("#form-acceptance").serialize()
    
    $.ajax({
        url: "/papers/ajax-change",
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
                html: "Processing your request...",      
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
                }).then(res=>{
                    window.location.reload()
                });
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

$(document).on("click", "#btn-change", function(e){
    e.preventDefault();
    $("#modal_change").modal("show")
    
});

', \yii\web\View::POS_READY);

?>
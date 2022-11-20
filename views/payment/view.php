<?php
use app\helpers\MyHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */

$this->title = 'Participant : '.(!empty($model->p) ? $model->p->name : null);
$this->params['breadcrumbs'][] = ['label' => 'Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$list_payment_validation = MyHelper::paymentValidation();
?>
<div class="block-header">
    <h2><?= Html::encode($this->title) ?></h2>
</div>
<div class="row">
   <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <?php 
                if(Yii::$app->user->can('finance')){
                    echo Html::a('<i class="fa fa-check"></i> Validation', ['validate', 'id' => $model->pay_id], [
                        'class' => 'btn btn-primary',
                        'id' => 'btn-validate'
                        
                    ]);
                }
                echo '&nbsp;';
                echo Html::a('<i class="fa fa-print"></i> Print Payment Proof', ['print', 'id' => $model->pay_id], [
                    'class' => 'btn btn-primary',
                    'target' => '_blank'
                    
                ]);
                 ?>
            </div>

            <div class="panel-body ">
        
<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            [
                'attribute' => 'pid',
                // 'contentOptions' => ['width' => '75%'],
                'value' => function($data){
                    return (!empty($data->p) ? $data->p->name : null);
                }
            ],
            [
                'label' => 'Email',
                // 'contentOptions' => ['width' => '75%'],
                'value' => function($data){
                    return (!empty($data->p) ? $data->p->email : null);
                }
            ],
            [
                'label' => 'Phone',
                // 'contentOptions' => ['width' => '75%'],
                'value' => function($data){
                    return (!empty($data->p) ? $data->p->phone : null);
                }
            ],
            [
                'attribute' => 'abs_id',
                
                'value' => function($data){
                    return (!empty($data->abs) ? $data->abs->abs_title : null);
                }
            ],
            'pay_created',
            'pay_date',

            'pay_method',
            'pay_origin',
            'pay_destination',
            'pay_currency',
            [
                'attribute' => 'pay_nominal',  
                'value' => function($data){
                    return MyHelper::formatRupiah($data->pay_nominal,2);
                }
            ],
            // 'pay_info:ntext',
            'pay_status',
            'valid_by',
            'valid_by_name',
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
        ],
    ]) ?>

            </div>
        </div>

    </div>
</div>



<?php


yii\bootstrap\Modal::begin([
'headerOptions' => ['id' => 'modalHeaderChange'],
'id' => 'modal',
'size' => 'modal-lg',
'clientOptions' => ['backdrop' => 'static', 'keyboard' => true]
]);
?>
<form action="" id="form-acceptance">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="">Validation Status <span style="color:red">*</span></label>
                <?=Html::radioList('pay_status','',$list_payment_validation,['separator' => '&nbsp;&nbsp;&nbsp;&nbsp;'])?>
                <?=Html::hiddenInput('pay_id',$model->pay_id,['id'=>'pay_id'])?>
            </div>
            
        </div>
        
        <div class="col-md-12">
            <div class="form-group">

                <?=Html::button('<i class="fa fa-save"></i> Update Now',['class' => 'btn btn-success btn-block btn-lg','id'=>'btn-simpan'])?>
            </div>
        </div>
    </div>
</form>
<?php
yii\bootstrap\Modal::end();
?>


<?php
        yii\bootstrap\Modal::begin(['id' =>'modal','size'=>'modal-lg',]);
        echo '<div class="text-center">';
        echo '<img width="100%" id="img">';
        echo '</div>';
        yii\bootstrap\Modal::end();
    ?>
<?php

$this->registerJs('

$(document).on("click", "#btn-simpan", function(e){
    e.preventDefault();
    
    var obj = $("#form-acceptance").serialize()
    
    $.ajax({
        url: "/payment/ajax-change",
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

$(document).on("click", "#btn-validate", function(e){
    e.preventDefault();
    $("#modal").modal("show")
    
});
');
?>
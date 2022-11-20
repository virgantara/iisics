<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CertificateType */

$this->title = 'CERT TYPE: '.$model->certificate_type_name;
$this->params['breadcrumbs'][] = ['label' => 'Certificate Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    @font-face { font-family: 'MyCustomFont'; src: url('<?=Url::to(['font-family','id'=>$model->id])?>'); } 
</style>
<div class="block-header">
    <h2><?= Html::encode($this->title) ?></h2>
</div>
<div class="row">
   <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <?= Html::a('Preview', ['preview', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            
            'certificate_type_name',
            'certificate_prefix_number',
            [
                'attribute' => 'certificate_template',
                'format' => 'raw',
                'value' => function($data){
                    if(!empty($data->certificate_template)){
                        return Html::a(Html::img(Url::to(['certificate-type/foto','id'=>$data->id]),['width'=>'120px']),'',['class'=>'popupModal','data-pjax'=>0,'data-item'=>Url::to(['certificate-type/foto','id'=>$data->id])]);
                    }
                        
                    else
                        return '';
                }
            ],
            [
                'attribute' => 'certificate_font_style',
                'format' => 'raw',
                'contentOptions' => ['height' => '120px','style'=>'vertical-align:middle'],
                'label' => 'Font Style Example' ,
                'value' => function($data){
                    return '<span style="font-size:3em;color:black;font-family:\'MyCustomFont\'">The quick brown fox jumps over the lazy dog</span>';
                }
            ],
        ],
    ]) ?>

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

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Certificate */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Certificates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-header">
    <h2><?= Html::encode($this->title) ?></h2>
</div>
<div class="row">
   <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <?= Html::a('<i class="fa fa-print"></i> Print', ['print', 'id' => $model->cert_id], ['class' => 'btn btn-success']) ?>
                <?= Html::a('<i class="fa fa-edit"></i> Update', ['update', 'id' => $model->cert_id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete', 'id' => $model->cert_id], [
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
            // 'cert_id',
            'cert_no',
            'pid',
            [
                'attribute' => 'abs_id',
                'label' => 'Abstract Title',
                'value' => function($data){
                    return (!empty($data->abs) ? $data->abs->abs_title : null);
                }
            ],
            'name',
            [
                'attribute' => 'type_id',
                // 'contentOptions' => ['width' => '25%'],
                'value' => function($data){
                    return (!empty($data->type) ? $data->type->certificate_type_name : null);
                }
            ],
        ],
    ]) ?>

            </div>
        </div>

    </div>
</div>
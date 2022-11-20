<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sponsor */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sponsors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-header">
    <h2><?= Html::encode($this->title) ?></h2>
</div>
<div class="row">
   <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
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
            [
                'attribute' => 'file_path',
                'format' => 'raw',
                'value' => function($data){
                    if(!empty($data->file_path)){
                        return Html::a(Html::img(Url::to(['sponsor/foto','id'=>$data->id]),['width'=>'120px']),'',['class'=>'popupModal','data-pjax'=>0,'data-item'=>Url::to(['sponsor/foto','id'=>$data->id])]);
                    }
                        
                    else
                        return '';
                }
            ],
            'sponsor_name',
            'sponsor_role',
            'sequence',
        ],
    ]) ?>

            </div>
        </div>

    </div>
</div>
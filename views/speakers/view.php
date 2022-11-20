<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Speakers */

$this->title = $model->speaker_name;
$this->params['breadcrumbs'][] = ['label' => 'Speakers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-header">
    <h2><?= Html::encode($this->title) ?></h2>
</div>
<div class="row">
   <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <?= Html::a('Update', ['update', 'id' => $model->speaker_id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->speaker_id], [
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
                'attribute' => 'speaker_image',
                'format' => 'raw',
                'value' => function($data){
                    if(!empty($data->speaker_image)){
                        // // return Html::img(Url::to(['simak-mastermahasiswa/foto','id'=>$data->id]),['width'=>'70px']);
                        // return Html::a(Html::img($data->foto_path,['width'=>'70px']),'',['class'=>'popupModal','data-pjax'=>0,'data-item'=>$data->foto_path]);
                        return Html::a(Html::img(Url::to(['speakers/foto','id'=>$data->speaker_id]),['width'=>'120px']),'',['class'=>'popupModal','data-pjax'=>0,'data-item'=>Url::to(['speakers/foto','id'=>$data->speaker_id])]);
                    }
                        
                    else
                        return '';
                }
            ],
            'speaker_name',
            'speaker_slug',
            'speaker_type',
            'speaker_institution',
            'speaker_content:html',
            
            'sequence',
        ],
    ]) ?>

            </div>
        </div>

    </div>
</div>
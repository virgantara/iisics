<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\System */

$this->title = $model->sys_id;
$this->params['breadcrumbs'][] = ['label' => 'Systems', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-header">
    <h2><?= Html::encode($this->title) ?></h2>
</div>
<div class="row">
   <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <?= Html::a('Update', ['update', 'sys_id' => $model->sys_id, 'sys_name' => $model->sys_name], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'sys_id' => $model->sys_id, 'sys_name' => $model->sys_name], [
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
            'sys_id',
            'sys_name',
            'sys_content',
        ],
    ]) ?>

            </div>
        </div>

    </div>
</div>
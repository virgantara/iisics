<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\System */

$this->title = 'Update System: ' . $model->sys_id;
$this->params['breadcrumbs'][] = ['label' => 'Systems', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sys_id, 'url' => ['view', 'sys_id' => $model->sys_id, 'sys_name' => $model->sys_name]];
$this->params['breadcrumbs'][] = 'Update';
?>
<h3><?= Html::encode($this->title) ?></h3>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body ">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
           </div>
        </div>
    </div>
</div>

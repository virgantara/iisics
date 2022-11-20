<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Papers */

$this->title = 'Update Papers: ' . $model->paper_id;
$this->params['breadcrumbs'][] = ['label' => 'Papers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->paper_id, 'url' => ['view', 'id' => $model->paper_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<h3><?= Html::encode($this->title) ?></h3>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body ">
    <?= $this->render('_form', [
        'model' => $model,
        'list_abs' => $list_abs
    ]) ?>
           </div>
        </div>
    </div>
</div>

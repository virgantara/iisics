<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */

$this->title = 'Update Payment: ' . $model->pay_id;
$this->params['breadcrumbs'][] = ['label' => 'Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pay_id, 'url' => ['view', 'id' => $model->pay_id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h3 class=""><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="x_content ">
    <?= $this->render('_form', [
        'model' => $model,
         'abs' => $abs
    ]) ?>
           </div>
        </div>
    </div>
</div>

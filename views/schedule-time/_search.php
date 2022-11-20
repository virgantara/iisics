<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ScheduleTimeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schedule-time-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'waktu_mulai') ?>

    <?= $form->field($model, 'waktu_selesai') ?>

    <?= $form->field($model, 'agenda') ?>

    <?= $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'day_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

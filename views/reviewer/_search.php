<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ReviewerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reviewer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'rev_id') ?>

    <?= $form->field($model, 'rev_email') ?>

    <?= $form->field($model, 'rev_password') ?>

    <?= $form->field($model, 'rev_type') ?>

    <?= $form->field($model, 'rev_name') ?>

    <?php // echo $form->field($model, 'rev_status') ?>

    <?php // echo $form->field($model, 'rev_enc') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

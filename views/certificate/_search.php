<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CertificateSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="certificate-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cert_id') ?>

    <?= $form->field($model, 'cert_no') ?>

    <?= $form->field($model, 'pid') ?>

    <?= $form->field($model, 'abs_id') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

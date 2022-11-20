<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pay_id') ?>

    <?= $form->field($model, 'pid') ?>

    <?= $form->field($model, 'abs_id') ?>

    <?= $form->field($model, 'pay_created') ?>

    <?= $form->field($model, 'pay_date') ?>

    <?php // echo $form->field($model, 'pay_file') ?>

    <?php // echo $form->field($model, 'pay_method') ?>

    <?php // echo $form->field($model, 'pay_origin') ?>

    <?php // echo $form->field($model, 'pay_destination') ?>

    <?php // echo $form->field($model, 'pay_currency') ?>

    <?php // echo $form->field($model, 'pay_nominal') ?>

    <?php // echo $form->field($model, 'pay_info') ?>

    <?php // echo $form->field($model, 'pay_status') ?>

    <?php // echo $form->field($model, 'valid_by') ?>

    <?php // echo $form->field($model, 'valid_by_name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

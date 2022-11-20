<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Certificate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="certificate-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cert_no',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'pid',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'abs_id',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'name',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'type',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

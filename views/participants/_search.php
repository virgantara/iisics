<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ParticipantsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="participants-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pid') ?>

    <?= $form->field($model, 'participant_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'name2') ?>

    <?= $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'institution') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'fax') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'password') ?>

    <?php // echo $form->field($model, 'registered') ?>

    <?php // echo $form->field($model, 'token') ?>

    <?php // echo $form->field($model, 'reset_key') ?>

    <?php // echo $form->field($model, 'activation_code') ?>

    <?php // echo $form->field($model, 'active') ?>

    <?php // echo $form->field($model, 'paid') ?>

    <?php // echo $form->field($model, 'enable') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'regsuccess') ?>

    <?php // echo $form->field($model, 'certificate') ?>

    <?php // echo $form->field($model, 'as_presenter') ?>

    <?php // echo $form->field($model, 'block') ?>

    <?php // echo $form->field($model, 'no_certificate') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

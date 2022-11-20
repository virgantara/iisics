<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SpeakersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="speakers-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'speaker_id') ?>

    <?= $form->field($model, 'speaker_name') ?>

    <?= $form->field($model, 'speaker_slug') ?>

    <?= $form->field($model, 'speaker_type') ?>

    <?= $form->field($model, 'speaker_institution') ?>

    <?php // echo $form->field($model, 'speaker_content') ?>

    <?php // echo $form->field($model, 'speaker_image') ?>

    <?php // echo $form->field($model, 'sequence') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HomecontentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="homecontent-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'page_id') ?>

    <?= $form->field($model, 'page_title') ?>

    <?= $form->field($model, 'page_slug') ?>

    <?= $form->field($model, 'page_description') ?>

    <?= $form->field($model, 'page_content') ?>

    <?php // echo $form->field($model, 'page_view') ?>

    <?php // echo $form->field($model, 'sequence') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

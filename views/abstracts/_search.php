<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AbstractsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="abstracts-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'abs_id') ?>

    <?= $form->field($model, 'pid') ?>

    <?= $form->field($model, 'topic_id') ?>

    <?= $form->field($model, 'abs_date') ?>

    <?= $form->field($model, 'abs_date_edit') ?>

    <?php // echo $form->field($model, 'abs_title') ?>

    <?php // echo $form->field($model, 'abs_author') ?>

    <?php // echo $form->field($model, 'abs_institution') ?>

    <?php // echo $form->field($model, 'abs_abstract') ?>

    <?php // echo $form->field($model, 'abs_keyword') ?>

    <?php // echo $form->field($model, 'abs_type') ?>

    <?php // echo $form->field($model, 'abs_status') ?>

    <?php // echo $form->field($model, 'abs_paid') ?>

    <?php // echo $form->field($model, 'presenter_name') ?>

    <?php // echo $form->field($model, 'examiner_by') ?>

    <?php // echo $form->field($model, 'rev_id') ?>

    <?php // echo $form->field($model, 'rev_name') ?>

    <?php // echo $form->field($model, 'viewed') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

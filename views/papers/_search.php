<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PapersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="papers-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'paper_id') ?>

    <?= $form->field($model, 'abs_id') ?>

    <?= $form->field($model, 'pid') ?>

    <?= $form->field($model, 'paper_file') ?>

    <?= $form->field($model, 'paper_raw') ?>

    <?php // echo $form->field($model, 'paper_ext') ?>

    <?php // echo $form->field($model, 'paper_date') ?>

    <?php // echo $form->field($model, 'paper_info') ?>

    <?php // echo $form->field($model, 'paper_status') ?>

    <?php // echo $form->field($model, 'paper_editor_comment') ?>

    <?php // echo $form->field($model, 'paper_final') ?>

    <?php // echo $form->field($model, 'paper_reviewed') ?>

    <?php // echo $form->field($model, 'paper_recomendation') ?>

    <?php // echo $form->field($model, 'paper_review_comment') ?>

    <?php // echo $form->field($model, 'paper_review_date') ?>

    <?php // echo $form->field($model, 'paper_review_file') ?>

    <?php // echo $form->field($model, 'paper_review_file_raw') ?>

    <?php // echo $form->field($model, 'paper_review_file_ext') ?>

    <?php // echo $form->field($model, 'paper_revised_file') ?>

    <?php // echo $form->field($model, 'paper_revised_file_raw') ?>

    <?php // echo $form->field($model, 'paper_revised_file_ext') ?>

    <?php // echo $form->field($model, 'paper_final_file') ?>

    <?php // echo $form->field($model, 'paper_final_file_raw') ?>

    <?php // echo $form->field($model, 'paper_final_file_ext') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

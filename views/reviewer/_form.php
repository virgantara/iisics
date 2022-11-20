<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Reviewer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reviewer-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']); ?>
    <?= $form->field($model, 'rev_email',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'rev_type',['options' => ['tag' => false]])->dropDownList(['papers'=>'papers']) ?>

    <?= $form->field($model, 'rev_name',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'rev_status',['options' => ['tag' => false]])->radioList(['active'=>'active','inactive' => 'inactive']) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

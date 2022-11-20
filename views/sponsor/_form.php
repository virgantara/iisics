<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sponsor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sponsor-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'form_validation',
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>
    <?php echo $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']); ?>
    <?= $form->field($model, 'sponsor_name',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'sponsor_role',['options' => ['tag' => false]])->radioList(['Host'=>'Host','Co-Host' => 'Co-Host']) ?>

    <?= $form->field($model, 'sequence',['options' => ['tag' => false]])->textInput() ?>
    <div class="form-group">
        <?= $form->field($model, 'file_path',['options' => ['tag' => false]])->fileInput(['class'=>'form-control','accept' => 'image/*']) ?>
        <small>Filetype: jpg, jpeg, png. Maxsize: 1MB</small>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

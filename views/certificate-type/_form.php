<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CertificateType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="certificate-type-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'form_validation',
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>
    <?= $form->field($model, 'certificate_type_name',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'certificate_prefix_number',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <div class="form-group">
        <?= $form->field($model, 'certificate_template',['options' => ['tag' => false]])->fileInput(['class'=>'form-control','accept' => 'image/*']) ?>
        <small>Filetype: jpg, jpeg, png. Maxsize: 2MB</small>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'certificate_font_style',['options' => ['tag' => false]])->fileInput(['class'=>'form-control','accept'=>'.ttf, .otf']) ?>
        <small>Filetype: ttf. Maxsize: 500KB</small>
    </div>
    <?= $form->field($model, 'certificate_font_size',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>
    <?= $form->field($model, 'certificate_text_top_position',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Speakers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="speakers-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'speaker_name',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'speaker_type',['options' => ['tag' => false]])->radioList([ 'Keynote' => 'Keynote', 'Invited' => 'Invited'], ['prompt' => '']) ?>

    <?= $form->field($model, 'speaker_institution',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'speaker_content',['options' => ['tag' => false]])->widget(CKEditor::className(), [
                'options' => ['rows' => 6],
                'preset' => 'advance',  
                'clientOptions'=>[
                    'enterMode' => 2,
                    'forceEnterMode'=>false,
                    'shiftEnterMode'=>1
                ]
            ]); ?>
    <div class="form-group">
        <?= $form->field($model, 'speaker_image',['options' => ['tag' => false]])->fileInput(['class'=>'form-control','accept' => 'image/*']) ?>

        <small>Filetype: jpg, jpeg, png. Maxsize: 1MB</small>
    </div>
    
    <?= $form->field($model, 'sequence',['options' => ['tag' => false]])->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

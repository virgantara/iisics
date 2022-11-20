<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
/* @var $this yii\web\View */
/* @var $model app\models\Pages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pages-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']); ?>
    <?= $form->field($model, 'page_title',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'page_slug',['options' => ['tag' => false]])->textInput(['class'=>'form-control','readonly' => true]) ?>

    <?= $form->field($model, 'page_description',['options' => ['tag' => false]])->widget(CKEditor::className(), [
                'options' => ['rows' => 6],
                'preset' => 'advance',  
                'clientOptions'=>[
                    'enterMode' => 2,
                    'forceEnterMode'=>false,
                    'shiftEnterMode'=>1
                ]
            ]); ?>

    <?= $form->field($model, 'page_content',['options' => ['tag' => false]])->widget(CKEditor::className(), [
                'options' => ['rows' => 6],
                'preset' => 'advance',  
                'clientOptions'=>[
                    'enterMode' => 2,
                    'forceEnterMode'=>false,
                    'shiftEnterMode'=>1
                ]
            ]); ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
/* @var $this yii\web\View */
/* @var $model app\models\PaperRevision */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="col-md-6">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'form_validation',
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>
     <?php echo $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']); ?>
    <?= $form->field($model, 'author_comment',['options' => ['tag' => false]])->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'advance',  
        'clientOptions'=>[
            'enterMode' => 2,
            'forceEnterMode'=>false,
            'shiftEnterMode'=>1
        ]
    ]); ?>

    <div class="form-group">
        <label for="">File Revision</label>
        <?= $form->field($model, 'paper_file',['options' => ['tag' => false]])->fileInput(['accept'=>'application/pdf','class'=>'form-control'])->label(false) ?>
        <small>Maxsize: 2 MB, filetype: pdf</small>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

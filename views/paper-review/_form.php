<?php
use app\helpers\MyHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
/* @var $this yii\web\View */
/* @var $model app\models\PaperReview */
/* @var $form yii\widgets\ActiveForm */

$list_status = MyHelper::reviewerResultStatus();
?>

<div class="paper-review-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'form_validation',
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>
    <?php echo $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']); ?>
    <h3>Abstract</h3>
    <p>
    <?php 
    if(!$model->isNewRecord){

        echo (!empty($model->abs) ? $model->abs->abs_abstract : '');
        echo '<h3>Paper info</h3><p>';
        echo $model->paper->paper_info;
        echo '</p><h3>Fullpaper</h3><p>';

        if(!empty($model->paper->paper_file))
            echo Html::a('<i class="fa fa-download"></i> Download',['papers/download','id' => $model->paper_id],['class' => 'btn btn-primary','target'=>'_blank']);
        else{
            echo '<span style="color:red">Not uploaded</span>';
        }
        echo '</p>';
    }
     ?>
    </p>
    <?= $form->field($model, 'comment_from_reviewer',['options' => ['tag' => false]])->widget(CKEditor::className(), [
                'options' => ['rows' => 6],
                'preset' => 'advance',  
                'clientOptions'=>[
                    'enterMode' => 2,
                    'forceEnterMode'=>false,
                    'shiftEnterMode'=>1
                ]
            ]); ?>
    <?= $form->field($model, 'acceptance_status',['options' => ['tag' => false]])->dropDownList($list_status,['class'=>'form-control']) ?>
    <div class="form-group">
        <label for="">File from Reviewer</label>
        <?= $form->field($model, 'file_path',['options' => ['tag' => false]])->fileInput(['accept'=>'application/pdf'])->label(false) ?>
        <small>Maxsize: 2 MB, filetype: pdf</small>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
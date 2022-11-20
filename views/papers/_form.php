<?php
use app\helpers\MyHelper;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Papers */
/* @var $form yii\widgets\ActiveForm */
// print_r($list_abs);exit;
$list_abs = ArrayHelper::map($list_abs,'abs_id','abs_title');

?>

<div class="papers-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'form_validation',
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>
    <div class="alert alert-info">
        <i class="fa fa-warning"></i> Only accepted abstracts are shown in here
    </div>
    <?php echo $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']); ?>
    <?= $form->field($model, 'abs_id')->widget(Select2::className(),[
        'data' => $list_abs,
        'options'=>['placeholder'=>Yii::t('app','- Choose Your Abstract -')],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <?=$form->field($model,'paper_info')->textInput(['class' => 'form-control'])?>

    
    <div class="form-group">
        <label for="">Upload your fullpaper</label>
        <?= $form->field($model, 'paper_file',['options' => ['tag' => false]])->fileInput(['accept'=>'application/pdf'])->label(false) ?>
        <small>Maxsize: 5 MB, filetype: pdf</small>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\ScheduleTime */
/* @var $form yii\widgets\ActiveForm */

$list_day = ArrayHelper::map(\app\models\ScheduleDay::find()->orderBy(['sequence'=>SORT_ASC])->all(),'id','day_name');
?>

<div class="schedule-time-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'day_id',['options' => ['tag' => false]])->dropDownList($list_day) ?>
    <?= $form->field($model, 'waktu_mulai',['options' => ['tag' => false]])->widget(DateTimePicker::classname(), [
                'options' => ['placeholder' => 'Enter tanggal mulai ...'],
                'pluginOptions' => [
                    'autoclose' => true
                ]
            ]) ?>
    <?= $form->field($model, 'waktu_selesai',['options' => ['tag' => false]])->widget(DateTimePicker::classname(), [
                'options' => ['placeholder' => 'Enter tanggal selesai ...'],
                'pluginOptions' => [
                    'autoclose' => true
                ]
            ]) ?>
    <?= $form->field($model, 'agenda',['options' => ['tag' => false]])->widget(CKEditor::className(), [
                'options' => ['rows' => 6],
                'preset' => 'advance',  
                'clientOptions'=>[
                    'enterMode' => 2,
                    'forceEnterMode'=>false,
                    'shiftEnterMode'=>1
                ]
            ]); ?>
     <?= $form->field($model, 'description',['options' => ['tag' => false]])->widget(CKEditor::className(), [
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

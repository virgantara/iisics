<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */
/* @var $form yii\widgets\ActiveForm */
$list_payment_method = MyHelper::paymentMethod();
$list_currency = ArrayHelper::map(\app\models\Currency::find()->orderBy(['code' => SORT_ASC])->all(),'code','code');
$list_bank = ArrayHelper::map(\app\models\BankAccount::find()->all(),'nama_bank','nama_bank');
$list_banks = ArrayHelper::map(\app\models\Bank::find()->orderBy(['code' => SORT_ASC])->all(),'name',function($data){
    return $data->code.' - '.$data->name;
});
?>

<div class="payment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pid',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'abs_id',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'pay_created',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'pay_date',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'pay_file',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'pay_method',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'pay_origin',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'pay_destination',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'pay_currency',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'pay_nominal',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'pay_info',['options' => ['tag' => false]])->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'pay_status',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'valid_by',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'valid_by_name',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

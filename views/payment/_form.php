<?php
use app\helpers\MyHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\datetime\DateTimePicker;
use kartik\number\NumberControl;
/* @var $this yii\web\View */
/* @var $abs app\models\Abstracts */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Payment';
$list_payment_method = MyHelper::paymentMethod();
$list_currency = ArrayHelper::map(\app\models\Currency::find()->orderBy(['code' => SORT_ASC])->all(),'code','code');
$list_bank = ArrayHelper::map(\app\models\BankAccount::find()->all(),'nama_bank','nama_bank');
$list_banks = ArrayHelper::map(\app\models\Bank::find()->orderBy(['code' => SORT_ASC])->all(),'name',function($data){
    return $data->code.' - '.$data->name;
});
?>

<div class="row">
    <div class="col-md-offset-3 col-md-6">

         <?php $form = ActiveForm::begin([
            'options' => [
                'id' => 'form_validation',
                'enctype' => 'multipart/form-data'
            ]
        ]); ?>
        <?php echo $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']); ?>
        <?= $form->field($model, 'pay_date',['options' => ['tag' => false]])->widget(DateTimePicker::classname(), [
                'options' => ['placeholder' => 'Enter payment date ...'],
                'pluginOptions' => [
                    'autoclose' => true
                ]
            ]) ?>
        <?= $form->field($model, 'pay_method')->widget(Select2::className(),[
            'data' => $list_payment_method,
            'options'=>['placeholder'=>Yii::t('app','- Choose Payment Method -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]) ?>
        <?= $form->field($model, 'pay_origin')->widget(Select2::className(),[
            'data' => $list_banks,
            'options'=>['placeholder'=>Yii::t('app','- Choose Your Bank Account -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]) ?>

        <?= $form->field($model, 'pay_destination')->widget(Select2::className(),[
            'data' => $list_bank,
            'options'=>['placeholder'=>Yii::t('app','- Choose Bank Account -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]) ?>
        <?= $form->field($model, 'pay_currency')->widget(Select2::className(),[
            'data' => $list_currency,
            'options'=>['placeholder'=>Yii::t('app','- Choose Currency -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]) ?>
        <?= $form->field($model, 'pay_nominal')->widget(NumberControl::className(),[
            'maskedInputOptions' => [
            // 'prefix' => 'Rp ',
            'groupSeparator' => '.',
            'radixPoint' => ','
        ]
        ]) ?>
        <?=$form->field($model,'pay_info')->textInput()?>
        <div class="form-group">
            <?= $form->field($model, 'pay_file')->fileInput(['class'=>'form-control','accept' => 'image/*']) ?>
            <small>Filetype: jpg, jpeg, png. Maxsize: 1MB</small>
        </div>
        
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\SignupForm */

use kartik\password\PasswordInput;
use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;

$this->title = Yii::t('app', 'Registration Form');
$this->params['breadcrumbs'][] = $this->title;

$list_country = ArrayHelper::map(\app\models\Country::find()->orderBy(['name' => SORT_ASC])->all(),'name','name');
?>
<style>
input[type="radio"] {
  -ms-transform: scale(1.5); /* IE 9 */
  -webkit-transform: scale(1.5); /* Chrome, Safari, Opera */
  transform: scale(1.5);
}
</style>
<div class="site-signup">
    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
    <h1 class="col-md-offset-3 "><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-md-offset-3 col-md-6 well">
            <h3><?= Yii::t('app', 'Basic Information:') ?></h3>
            <?= $form->field($participant, 'name',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

            <?= $form->field($participant, 'name2',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

            <?= $form->field($participant, 'gender',['options' => ['tag' => false]])->inline(true)->radioList(['Male'=>'Male','Female' => 'Female']) ?>

            <?= $form->field($participant, 'type',['options' => ['tag' => false]])->inline(true)->radioList(['Presenter'=>'Presenter','Non-Presenter' => 'Non-Presenter']) ?>

            <?= $form->field($participant, 'institution',['options' => ['tag' => false]])->textArea(['class'=>'form-control']) ?>

            <?= $form->field($participant, 'address',['options' => ['tag' => false]])->textArea(['class'=>'form-control']) ?>

            <?= $form->field($participant, 'country',['options' => ['tag' => false]])->widget(Select2::className(),[
                'data' => $list_country,
                'options'=>['placeholder'=>Yii::t('app','- Choose Country -')],
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]) ?>

            <?= $form->field($participant, 'phone',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

            <?= $form->field($participant, 'fax',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-3 col-md-6 well">

            <h3><?= Yii::t('app', 'Login Information:') ?></h3>

            

                <?= $form->field($model, 'username')->textInput(
                    ['placeholder' => Yii::t('app', 'Create your username')]) ?>

                <?= $form->field($model, 'email')->input('email', ['placeholder' => Yii::t('app', 'Enter your e-mail')]) ?>

                <?= $form->field($model, 'password')->widget(PasswordInput::classname(), 
                    ['options' => ['placeholder' => Yii::t('app', 'Create your password')]]) ?>
               <?= $form->field($model, 'captcha')->widget(Captcha::className(), 
            ['template' => '<div class="captcha_img">{image}</div>'
                . '<a class="refreshcaptcha" href="#"></a>'
                . 'Verification Code{input}',
            ])->label(FALSE); ?> 
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'Signup'), 
                        ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            

            <?php if ($model->scenario === 'rna'): ?>
                <div style="color:#666;margin:1em 0">
                    <i>*<?= Yii::t('app', 'We will send you an email with account activation link.') ?></i>
                </div>
            <?php endif ?>

        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
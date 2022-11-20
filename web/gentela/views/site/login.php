<?php 
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<h1>Login to <?=Yii::$app->params['shortname']?></h1>
<?php 
$form = ActiveForm::begin(['class' => 'form-auth-small']); 
echo $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);
?>
<div>
    <?= $form->field($model, 'username',['options' => ['tag' => false]])->textInput(['placeholder' => Yii::t('app', 'Enter your username' ), 'autofocus' => true,'class'=>'form-control'])->label(false) ?>
</div>
  <div>
    <?= $form->field($model, 'password',['options' => ['tag' => false]])->passwordInput(['placeholder' => Yii::t('app', 'Enter your password'),'class'=>'form-control'])->label(false) ?>
  </div>
  <div>
    <button type="submit" class="btn btn-default submit" href="index.html">Log in</button>
    <a class="reset_pass" href="<?=Url::to(['site/request-password-reset'])?>">Lost your password?</a>
  </div>

  <div class="clearfix"></div>

  <div class="separator">
    <p class="change_link">New to site?
      <a href="<?=Url::to(['site/signup'])?>" class="to_register"> Create Account </a>
    </p>

    <div class="clearfix"></div>
    <br />

    
  </div>
<?php ActiveForm::end(); ?>


<?php

$this->registerJs('

');

?>
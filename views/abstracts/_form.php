<?php
use app\helpers\MyHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Abstracts */
/* @var $form yii\widgets\ActiveForm */

$list_topic = \app\models\Topics::find()->all();
$list_status_abstrak = MyHelper::statusAbstract();
?>
<style>
input[type="radio"] {
  -ms-transform: scale(1.5); /* IE 9 */
  -webkit-transform: scale(1.5); /* Chrome, Safari, Opera */
  transform: scale(1.5);
}
</style>
<div class="abstracts-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php echo $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']); ?>

    <?= $form->field($model, 'topic_id',['options' => ['tag' => false]])->dropDownList(ArrayHelper::map($list_topic,'topic_id','topic_title')) ?>

    <?= $form->field($model, 'abs_title',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <div class="form-group">
        <label for="">Author(s)</label>
    <?= $form->field($model, 'abs_author',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true])->label(false) ?>
    <small>Write name without academic degree and separate with semicolon (;)</small>
    </div>

    <?= $form->field($model, 'abs_institution',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'abs_abstract',['options' => ['tag' => false]])->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <label for="">Keywords</label>
    <?= $form->field($model, 'abs_keyword',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true])->label(false) ?>
    <small>Separate with semicolon (;), max 5 words</small>
    </div>
    <?= $form->field($model, 'abs_type',['options' => ['tag' => false]])->dropDownList(['Oral Presentation'=>'Oral Presentation','Poster Presentation' => 'Poster Presentation']) ?>
    <?= $form->field($model, 'presenter_name',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

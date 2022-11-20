<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Certificate */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Generate Certificate';
$this->params['breadcrumbs'][] = ['label' => 'Certificate', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="certificate-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'id',['options' => ['tag' => false]])->dropDownList(ArrayHelper::map($list_types,'id','certificate_type_name')) ?>
    

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

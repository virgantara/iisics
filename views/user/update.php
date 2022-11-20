<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = 'Update User: ' . $model->NIY;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->NIY, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">

    
    <?= $this->render('_form', [
        'model' => $model,
        'dataDiri' => $dataDiri,
        'listKampus' => $listKampus
    ]) ?>
		
</div>

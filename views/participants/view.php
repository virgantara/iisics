<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Participants */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Participants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-header">
    <h2><?= Html::encode($this->title) ?></h2>
</div>
<div class="row">
   <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <?= Html::a('Update', ['update', 'id' => $model->pid], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->pid], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>

            <div class="panel-body ">
        
<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'pid',
            'participant_id',
            'name',
            'name2',
            'gender',
            'type',
            'institution',
            'address',
            'country',
            'phone',
            'fax',
            'email:email',
            'password',
            'registered',
            'token',
            'reset_key',
            'activation_code',
            'active',
            'paid',
            'enable',
            'status',
            'regsuccess',
            'certificate',
            'as_presenter',
            'block',
            'no_certificate',
        ],
    ]) ?>

            </div>
        </div>

    </div>
</div>
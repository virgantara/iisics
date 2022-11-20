<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Prodi;
use kartik\depdrop\DepDrop;
use kartik\date\DatePicker;

use \app\models\MJenjangPendidikan;
/* @var $this yii\web\View */
/* @var $user backend\models\User */
/* @var $form yii\widgets\ActiveForm */

$listData = \app\models\MJabatanAkademik::getList();
$listDataJenjang = \app\models\MJenjangPendidikan::getList();
// $listRoles = \app\rbac\models\AuthItem::find()->where(['<>','name','theCreator'])->all();
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); 
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
      echo '<div class="alert alert-' . $key . '">' . $message . '<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button></div>';
    }
    echo $form->errorSummary($user,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);
    ?>
    <div class="row">
        
        <div class="col-md-6">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">Data Satker/Fakultas</h3>
                </div>
                <div class="panel-body">
                    
                            <?= $form->field($user, 'nama')->textInput(['autofocus' => true]) ?>
                            <?= $form->field($user, 'username')->textInput() ?>
                            <?= $form->field($user, 'satker_id')->dropDownList(
                            ArrayHelper::map(\app\models\UnitKerja::find()->orderBy(['nama'=>SORT_ASC])->all(),'id','nama'),
                            ['prompt'=>'Pilih Satuan Kerja']
                            ) 
                        ?>
                        <?= $form->field($user, 'fakultas_id')->dropDownList(ArrayHelper::map(\app\models\Fakultas::find()->all(),'ID','nama')) ?>
                        <?= $form->field($user, 'email') ?>
                            <?= $form->field($user, 'password')->passwordInput() ?>
                            
                        <?= $form->field($user, 'access_role')->dropDownList(ArrayHelper::map($authItems,'name','name')) ?>

                        <?= $form->field($user, 'uuid')->textInput() ?>
                    
                        <?= $form->field($user, 'status')->dropDownList(['aktif'=>'Aktif','nonaktif'=>'Nonaktif']) ?>
                        <div class="form-group">
                        <?= Html::submitButton($user->isNewRecord ? 'Create' : 'Update', ['class' => $user->isNewRecord ? 'btn btn-success btn-block btn-lg' : 'btn btn-primary btn-block btn-lg']) ?>
                        </div>
                        
                </div>
            </div>

        </div>                
    </div>
    <?php ActiveForm::end(); ?>

</div>


<?php

$this->registerJs(' 
 
', \yii\web\View::POS_READY);

?>

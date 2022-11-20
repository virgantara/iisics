<?php
use app\rbac\models\AuthItem;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
$authItems = AuthItem::find()->select(['name'])->where(['<>','name','theCreator'])->all();
/* @var $this yii\web\View */
/* @var $model backend\models\User */
$nama = '';
if(!empty($model->dataDiri))
{
  $nama = $model->dataDiri->nama;
}

else if(!empty($model->tendik))
{
  $nama = $model->tendik->nama;
}


$this->title = 'User: '.$nama;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$list_roles = ArrayHelper::map($authItems,'name','name');

// foreach (AuthItem::getRoles() as $item_name){
//     $roles[$item_name->name] = $item_name->name;  
// }
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
            <div class="panel-body">
                    <p>
                        <?php 
                        if(Yii::$app->user->can('admin'))
                        {
                        ?>
                        <?= Html::a('Edit', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Delete', ['delete', 'id' => $model->ID], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                        <?php 
                        }
                        ?>
                       
                    </p>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            
                            'username',
                            'email',
                            'fullname',
                            [
                              'attribute' => 'status',
                              'value' => function($data){
                                return ($data->status == 10 ? 'Aktif' : 'Non-Aktif');
                              }
                            ],
                            'access_role',
                            // 'created_at',
                            // 'updated_at',
                        ],
                    ]) ?>
            </div>
        </div>
    
    </div>

</div>


 <?php 
  if(Yii::$app->user->can('admin'))
  {



  ?>
  <div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h1>Roles</h1>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Role</th>
                      <th>Option</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    foreach($model->authAssignments as $role)
                    {
                    ?>
                    <tr>
                      <td><?=$role->item_name;?></td>
                      <td><?=Html::a('<i class="fa fa-trash"></i> Remove','javascript:void(0)',['class'=>'btn btn-danger btn-remove-role','data-item'=>$role->item_name,'data-user'=>$model->id]);?></td>
                    </tr>
                    <?php 
                    }
                    ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="2">
                        <?=Html::a('<i class="fa fa-plus"></i> Add a Role','javascript:void(0)',['class'=>'btn btn-success','id'=>'btn-add-role']);?>
                      </td>
                    </tr>
                  </tfoot>
                </table>
                </div>
            </div>
        </div>
    
    </div>

</div>

   <?php 
  }
   ?>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Data Role</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <input type="hidden" id="user_id" value="<?=$model->id;?>">
        <?=Html::dropDownList('item_name','',$list_roles,['id'=>'item_name','class'=>'form-control']);?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn-save">Add this role</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>
<?php 

$script = ' 

$(document).on("click","#btn-add-role",function(e){
  e.preventDefault()

  $("#exampleModal").modal("show");

  

})

$(document).on(\'change\',\'#dosen\',function(e){
    e.preventDefault();
    $("#nim").val($(this).val());
    
});

$(document).on("click","#btn-save",function(e){
  e.preventDefault()

  var obj = new Object;
  obj.user_id = $("#user_id").val();
  obj.item_name = $("#item_name").val();
  
  $.ajax({
      type: \'POST\',
      url: "'.Url::to(['user/ajax-add-role']).'",
      data: {
          dataPost : obj
      },
      async: true,
      error : function(e){
        Swal.hideLoading();
        

      },
      beforeSend: function(){
        Swal.showLoading();
      },
      success: function (data) {
        var hasil = $.parseJSON(data)
        if(hasil.code == 200){
      
          Swal.fire({
            title: \'Yeay!\',
            icon: \'success\',
            text: hasil.message
          }).then((result) => {
            if (result.value) {
              location.reload(); 
            }
          });
        }

        else{
          Swal.fire({
            title: \'Oops!\',
            icon: \'error\',
            text: hasil.message
          }).then((result) => {
            if (result.value) {
              location.reload(); 
            }
          });
        }
      }
  })
})


$(document).on("click",".btn-remove-role",function(e){
  e.preventDefault()



  var obj = new Object;
  obj.user_id = $(this).data("user");
  obj.item_name = $(this).data("item");
  
  $.ajax({
      type: \'POST\',
      url: "'.Url::to(['user/ajax-delete-role']).'",
      data: {
          dataPost : obj
      },
      async: true,
      error : function(e){
        Swal.hideLoading();
        

      },
      beforeSend: function(){
        Swal.showLoading();
      },
      success: function (data) {
        var hasil = $.parseJSON(data)
        if(hasil.code == 200){
      
          Swal.fire({
            title: \'Yeay!\',
            icon: \'success\',
            text: hasil.message
          }).then((result) => {
            if (result.value) {
              location.reload(); 
            }
          });
        }

        else{
          Swal.fire({
            title: \'Oops!\',
            icon: \'error\',
            text: hasil.message
          }).then((result) => {
            if (result.value) {
              location.reload(); 
            }
          });
        }
      }
  })
})

';

$this->registerJs($script, \yii\web\View::POS_READY);

?>
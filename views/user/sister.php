<?php
use app\rbac\models\AuthItem;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
$authItems = AuthItem::find()->select(['name'])->where(['<>','name','theCreator'])->all();
/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = 'Pemetaan SISTER SIMPEG';
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
                <?php 
                    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                      echo '<div class="alert alert-' . $key . '">' . $message . '<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button></div>';
                    }
                    ?>
                    <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Dosen/Tendik</th>
                          <th>Jenis SDM</th>
                          <!-- <th>Fakultas / Prodi</th> -->
                          <th>SISTER ID</th>
                          <th>Data Dosen SIMPEG</th>
                          <th>Status</th>
                          <th>Option</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 

                        foreach($results as $q => $item)
                        {

                          $user = \app\models\User::findOne(['sister_id'=>$item->id_sdm]);
                        ?>
                        <tr>
                          <td><?=$q+1;?></td>
                          <td><?=$item->nama_sdm;?></td>
                          <td><?=$item->jenis_sdm;?></td>
                          <td><?=!empty($user) ? $user->sister_id : null;?></td>
                          <td>
                            <?php 

                            if($item->jenis_sdm == 'Dosen')
                            {
                              if(empty($user))
                              {
                              ?>
                              <input type="text" placeholder="Ketik nama dosen" class="nama_dosen">
                              <?php 
                              }
                              else{
                                echo '-';
                              }
                            }

                            else if($item->jenis_sdm == 'Tenaga Kependidikan')
                            {
                              if(empty($user))
                              {
                              ?>
                              <input type="text" placeholder="Ketik nama tendik" class="nama_tendik">
                              <?php 
                              }
                              else{
                                echo '-';
                              }
                            }

                            
                            ?>
                          </td>
                          <td>
                            <div class="message">
                            <?php 
                            if(!empty($user))
                            {
                              echo '<label class="label label-success">Synced</label>';
                            }

                            else
                            {
                              echo '<label class="label label-danger">Not Synced</label>';
                            }
                            ?>
                          </div>
                          </td>
                          <td class="opsi">
                            <input type="hidden" class="id_dosen">
                            <?=Html::a('Update','#',['class'=>'btn-update-sister btn btn-primary','data-item'=>$item->id_sdm,'data-jenis'=>$item->jenis_sdm]);?>
                              
                          </td>
                        </tr>
                        <?php 
                        }
                        ?>
                      </tbody>
                     
                    </table>
                </div>
            </div>
        </div>
    
    </div>

</div>


<?php 

$script = ' 


$(document).bind("keyup.autocomplete",function(){

    

    $(".nama_dosen").autocomplete({
        minLength:2,
        select:function(event, ui){
       
            $(this).parent().parent().find(".opsi").find(".id_dosen").val(ui.item.id);
                
        },
      
        focus: function (event, ui) {
            $(this).parent().parent().find(".opsi").find(".id_dosen").val(ui.item.id);
        },
        source:function(request, response) {
            $.getJSON("'.Url::to(["user/ajax-cari-user"]).'", {
              term : request.term,
              
            },response)
            
        },
       
    });

    $(".nama_tendik").autocomplete({
        minLength:2,
        select:function(event, ui){
       
            $(this).parent().parent().find(".opsi").find(".id_dosen").val(ui.item.id);
                
        },
      
        focus: function (event, ui) {
            $(this).parent().parent().find(".opsi").find(".id_dosen").val(ui.item.id);
        },
        source:function(request, response) {
            $.getJSON("'.Url::to(["user/ajax-cari-user-tendik"]).'", {
              term : request.term,
              
            },response)
            
        },
       
    });
}); 

$(document).on("click",".btn-update-sister",function(e){
  e.preventDefault()



  var obj = new Object;
  obj.user_id = $(this).prev().val();
  obj.sister_id = $(this).data("item");
  obj.jenis_sdm = $(this).data("jenis")
  
  $.ajax({
      type: \'POST\',
      url: "'.Url::to(['user/ajax-update-sister']).'",
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